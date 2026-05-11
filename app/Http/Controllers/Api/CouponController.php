<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCode(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $coupon = Coupon::query()->whereRaw('UPPER(code) = ?', [strtoupper($data['code'])])->first();
        if (! $coupon || ! $coupon->isCurrentlyValid()) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired coupon'], 422);
        }

        if ($coupon->min_order_total !== null && $data['subtotal'] < $coupon->min_order_total) {
            return response()->json([
                'valid' => false,
                'message' => 'Order does not meet minimum for this coupon',
            ], 422);
        }

        $discount = $coupon->type === 'percent'
            ? round($data['subtotal'] * ((float) $coupon->value / 100), 2)
            : min((float) $coupon->value, (float) $data['subtotal']);

        return response()->json([
            'valid' => true,
            'coupon_id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
        ]);
    }
}
