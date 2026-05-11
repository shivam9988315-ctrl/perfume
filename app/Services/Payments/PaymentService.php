<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class PaymentService
{
    public function createStripeIntent(Order $order): ?array
    {
        $key = config('services.stripe.secret');
        if (! $key) {
            Log::warning('Stripe secret not configured; skipping PaymentIntent creation.');

            return null;
        }

        $stripe = new StripeClient($key);
        $intent = $stripe->paymentIntents->create([
            'amount' => (int) round($order->grand_total * 100),
            'currency' => strtolower((string) $order->currency),
            'metadata' => ['order_id' => (string) $order->id],
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        PaymentTransaction::query()->create([
            'order_id' => $order->id,
            'provider' => 'stripe',
            'provider_intent_id' => $intent->id,
            'status' => $intent->status ?? 'requires_payment_method',
            'amount' => $order->grand_total,
            'currency' => $order->currency,
            'payload' => ['client_secret' => $intent->client_secret],
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'publishable_key' => config('services.stripe.key'),
        ];
    }

    public function logCod(Order $order): PaymentTransaction
    {
        return PaymentTransaction::query()->create([
            'order_id' => $order->id,
            'provider' => 'cod',
            'provider_intent_id' => null,
            'status' => 'pending_collection',
            'amount' => $order->grand_total,
            'currency' => $order->currency,
            'payload' => null,
        ]);
    }
}
