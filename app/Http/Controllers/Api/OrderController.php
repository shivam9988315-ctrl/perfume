<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart\CartService;
use App\Services\Payments\PaymentService;
use App\Services\Shipping\ShippingQuoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct(
        protected CartService $carts,
        protected ShippingQuoteService $shipping,
        protected PaymentService $payments,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->orderByDesc('id')
            ->paginate(15);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return response()->json($order->load(['items', 'payments', 'shipments']));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:stripe,paypal,cod'],
            'shipping_address' => ['required', 'array'],
            'billing_address' => ['nullable', 'array'],
            'coupon_code' => ['nullable', 'string'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        $cart = $this->carts->getOrCreateForUser($user);
        $cart->load(['items.variant.product']);

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $subtotal = 0;
        foreach ($cart->items as $line) {
            $v = $line->variant;
            if (! $v || ! $v->is_active) {
                return response()->json(['message' => 'Cart contains an invalid variant'], 422);
            }
            if ($v->track_inventory && $v->stock_quantity < $line->quantity) {
                return response()->json(['message' => 'Insufficient stock for '.$v->sku], 422);
            }
            $subtotal += (float) $v->price * $line->quantity;
        }

        $discount = 0;
        $couponId = null;
        if (! empty($data['coupon_code'])) {
            $coupon = Coupon::query()->whereRaw('UPPER(code) = ?', [strtoupper($data['coupon_code'])])->first();
            if ($coupon && $coupon->isCurrentlyValid()) {
                if ($coupon->min_order_total === null || $subtotal >= $coupon->min_order_total) {
                    $couponId = $coupon->id;
                    $discount = $coupon->type === 'percent'
                        ? round($subtotal * ((float) $coupon->value / 100), 2)
                        : min((float) $coupon->value, $subtotal);
                }
            }
        }

        $country = $data['shipping_address']['country'] ?? 'US';
        $ship = $this->shipping->calculate($subtotal - $discount, is_string($country) ? $country : 'US');
        $shippingTotal = (float) $ship['amount'];

        $taxable = max(0, $subtotal - $discount + $shippingTotal);
        $taxRate = (float) config('shop.tax_rate', 0);
        $taxTotal = round($taxable * $taxRate, 2);
        $grand = round($subtotal - $discount + $shippingTotal + $taxTotal, 2);

        $order = DB::transaction(function () use ($cart, $user, $data, $subtotal, $discount, $taxTotal, $shippingTotal, $grand, $couponId, $ship) {
            $order = Order::query()->create([
                'order_number' => strtoupper(Str::random(12)),
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'coupon_id' => $couponId,
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'tax_total' => $taxTotal,
                'shipping_total' => $shippingTotal,
                'grand_total' => $grand,
                'currency' => $user->currency ?? config('shop.default_currency'),
                'shipping_address' => $data['shipping_address'],
                'billing_address' => $data['billing_address'] ?? $data['shipping_address'],
                'customer_note' => $data['customer_note'] ?? null,
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $line) {
                $v = $line->variant;
                $p = $v->product;
                $lineTotal = round((float) $v->price * $line->quantity, 2);
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'product_variant_id' => $v->id,
                    'product_name' => $p->name,
                    'variant_name' => $v->name,
                    'quantity' => $line->quantity,
                    'unit_price' => $v->price,
                    'line_total' => $lineTotal,
                ]);
                if ($v->track_inventory) {
                    $v->decrement('stock_quantity', $line->quantity);
                }
            }

            if ($couponId) {
                Coupon::query()->whereKey($couponId)->increment('uses_count');
            }

            $order->shipments()->create([
                'carrier' => $ship['carrier'] ?? 'standard',
                'service_code' => $ship['service_code'] ?? null,
                'status' => 'pending',
                'estimated_delivery_at' => $this->shipping->estimatedDelivery(
                    $ship['estimated_days_min'] ?? null,
                    $ship['estimated_days_max'] ?? null
                ),
            ]);

            $cart->items()->delete();

            return $order;
        });

        $stripe = null;
        if ($data['payment_method'] === 'stripe') {
            $stripe = $this->payments->createStripeIntent($order);
        }
        if ($data['payment_method'] === 'cod') {
            $this->payments->logCod($order);
            $order->update(['payment_status' => 'pending']);
        }
        if ($data['payment_method'] === 'paypal') {
            $order->payments()->create([
                'provider' => 'paypal',
                'status' => 'created',
                'amount' => $order->grand_total,
                'currency' => $order->currency,
                'payload' => ['note' => 'Complete PayPal capture with srmklive/paypal SDK in webhook/controller'],
            ]);
        }

        return response()->json([
            'data' => $order->fresh(['items', 'payments', 'shipments']),
            'stripe' => $stripe,
        ], 201);
    }
}
