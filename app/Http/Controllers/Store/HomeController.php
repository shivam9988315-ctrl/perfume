<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('store.home', [
            'banners' => Banner::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'featured' => Product::query()->where('is_active', true)->where('is_featured', true)->with(['brand', 'images', 'variants'])->take(8)->get(),
            'newArrivals' => Product::query()->where('is_active', true)->where('is_new', true)->with(['brand', 'images', 'variants'])->take(8)->get(),
            'bestSellers' => Product::query()->where('is_active', true)->where('is_bestseller', true)->with(['brand', 'images', 'variants'])->take(8)->get(),
            'onSale' => Product::query()->where('is_active', true)->where('is_on_sale', true)->with(['brand', 'images', 'variants'])->take(8)->get(),
            'brands' => Brand::query()->where('is_active', true)->orderBy('sort_order')->take(12)->get(),
        ]);
    }
}
