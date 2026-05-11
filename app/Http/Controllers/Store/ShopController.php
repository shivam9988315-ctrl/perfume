<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['brand', 'category', 'images', 'variants']);

        if ($request->filled('gender')) {
            $query->where('gender', $request->string('gender'));
        }
        if ($request->filled('search')) {
            $s = '%'.$request->string('search').'%';
            $query->where(fn ($q) => $q->where('name', 'like', $s)->orWhere('sku', 'like', $s));
        }

        $products = $query->orderByDesc('id')->paginate(12)->withQueryString();

        return view('store.shop.index', compact('products'));
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['brand', 'category', 'images', 'variants', 'reviews' => fn ($q) => $q->where('is_approved', true)->with('user:id,name')])
            ->firstOrFail();

        $related = Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images', 'variants'])
            ->take(4)
            ->get();

        return view('store.shop.show', compact('product', 'related'));
    }
}
