<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;

class CartService
{
    public function getOrCreateForUser(User $user): Cart
    {
        return Cart::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['session_id' => null]
        );
    }

    public function addItem(Cart $cart, int $variantId, int $quantity = 1): CartItem
    {
        $variant = ProductVariant::query()
            ->whereKey($variantId)
            ->where('is_active', true)
            ->firstOrFail();

        if ($variant->track_inventory && $variant->stock_quantity < $quantity) {
            abort(422, 'Insufficient stock for this variant.');
        }

        $item = CartItem::query()->firstOrNew([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
        ]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $quantity;
        $item->save();

        return $item;
    }
}
