<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id', 'carrier', 'service_code', 'tracking_number', 'label_url', 'status',
        'estimated_delivery_at', 'shipped_at', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'estimated_delivery_at' => 'datetime',
            'shipped_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
