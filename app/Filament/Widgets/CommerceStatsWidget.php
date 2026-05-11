<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommerceStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $revenue = (float) Order::query()->sum('grand_total');
        $ordersCount = Order::query()->count();
        $productsCount = Product::query()->where('is_active', true)->count();
        $customers = User::query()->role('customer')->count();

        return [
            Stat::make('Revenue (paid)', '$'.number_format($revenue, 2))
                ->description('All-time paid orders'),
            Stat::make('Orders', (string) $ordersCount)
                ->description('Total placed'),
            Stat::make('Active products', (string) $productsCount)
                ->description('Catalog size'),
            Stat::make('Customers', (string) $customers)
                ->description('Users with customer role'),
        ];
    }
}
