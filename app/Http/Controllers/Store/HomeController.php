<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $with = ['brand', 'category', 'images', 'variants'];

        $oudBase = fn () => Product::query()
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('product_type', 'oud')
                    ->orWhereHas('category', fn ($c) => $c->where('slug', 'oud'));
            });

        $arabicBase = fn () => Product::query()
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('product_type', 'arabic_collection')
                    ->orWhereHas('category', fn ($c) => $c->where('slug', 'arabic-collection'));
            });

        return view('store.home', [
            'banners' => Banner::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'bestSellers' => Product::query()->where('is_active', true)->where('is_bestseller', true)->with($with)->orderByDesc('id')->take(8)->get(),
            'newArrivals' => Product::query()->where('is_active', true)->where('is_new', true)->with($with)->orderByDesc('id')->take(8)->get(),
            'exclusiveAttars' => Product::query()
                ->where('is_active', true)
                ->where(function ($q): void {
                    $q->where('product_type', 'attar')
                        ->orWhereHas('category', fn ($c) => $c->where('slug', 'attars'));
                })
                ->with($with)
                ->orderByDesc('id')
                ->take(8)
                ->get(),
            'oudCollection' => $oudBase()->with($with)->orderByDesc('id')->take(8)->get(),
            'arabicFragrances' => $arabicBase()->with($with)->orderByDesc('id')->take(8)->get(),
            'limitedEdition' => Product::query()->where('is_active', true)->where('is_limited_edition', true)->with($with)->orderByDesc('id')->take(8)->get(),
            'onSale' => Product::query()->where('is_active', true)->where('is_on_sale', true)->with($with)->orderByDesc('id')->take(8)->get(),
            'brands' => Brand::query()->where('is_active', true)->orderBy('sort_order')->take(12)->get(),
            'testimonials' => Testimonial::query()->where('is_active', true)->orderBy('sort_order')->take(8)->get(),
        ]);
    }
}
