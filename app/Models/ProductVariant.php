<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'sku', 'size_label', 'price', 'stock_quantity',
        'low_stock_threshold', 'track_inventory', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'track_inventory' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isLowStock(): bool
    {
        if (! $this->track_inventory) {
            return false;
        }

        return $this->stock_quantity <= $this->low_stock_threshold;
    }
}
