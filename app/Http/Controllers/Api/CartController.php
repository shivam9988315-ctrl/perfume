<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $carts,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $cart = $this->carts->getOrCreateForUser($user);
        $cart->load(['items.variant.product.images']);

        $lines = $cart->items->map(function (CartItem $item) {
            $v = $item->variant;
            $p = $v?->product;

            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'variant' => $v,
                'product' => $p,
                'line_total' => $v ? (float) $v->price * $item->quantity : 0,
            ];
        });

        $subtotal = $lines->sum('line_total');

        return response()->json([
            'cart_id' => $cart->id,
            'items' => $lines,
            'subtotal' => $subtotal,
            'currency' => $user->currency ?? config('shop.default_currency'),
        ]);
    }

    public function addItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->carts->getOrCreateForUser($request->user());
        $item = $this->carts->addItem($cart, (int) $data['product_variant_id'], (int) ($data['quantity'] ?? 1));

        return response()->json(['data' => $item->load('variant.product')], 201);
    }

    public function updateItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorizeCartItem($request, $cartItem);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $variant = $cartItem->variant;
        if ($variant->track_inventory && $variant->stock_quantity < $data['quantity']) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }

        $cartItem->update(['quantity' => $data['quantity']]);

        return response()->json(['data' => $cartItem->fresh('variant.product')]);
    }

    public function removeItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorizeCartItem($request, $cartItem);
        $cartItem->delete();

        return response()->json(['message' => 'Removed']);
    }

    protected function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        abort_if(
            $cartItem->cart?->user_id !== $request->user()->id,
            403,
            'This cart item does not belong to you.'
        );
    }
}
