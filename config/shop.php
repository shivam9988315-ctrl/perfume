<?php

return [
    'tax_rate' => (float) env('SHOP_TAX_RATE', 0.08),
    'shipping_flat_rate' => (float) env('SHOP_SHIPPING_FLAT', 12.99),
    'shipping_free_above' => (float) env('SHOP_SHIPPING_FREE_ABOVE', 150),
    'default_currency' => env('SHOP_DEFAULT_CURRENCY', 'USD'),
];
