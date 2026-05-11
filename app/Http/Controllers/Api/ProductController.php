<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['brand', 'category', 'images', 'variants' => fn ($q) => $q->where('is_active', true)]);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }
        if ($request->filled('brand')) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $request->string('brand')));
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->string('gender'));
        }
        if ($request->filled('fragrance_type')) {
            $query->where('fragrance_type', 'like', '%'.$request->string('fragrance_type').'%');
        }
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->float('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->float('max_price'));
        }
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->boolean('new')) {
            $query->where('is_new', true);
        }
        if ($request->boolean('bestseller')) {
            $query->where('is_bestseller', true);
        }
        if ($request->boolean('sale')) {
            $query->where('is_on_sale', true);
        }
        if ($request->filled('search')) {
            $s = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)->orWhere('sku', 'like', $s);
            });
        }

        $sort = (string) $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('base_price'),
            'price_desc' => $query->orderByDesc('base_price'),
            default => $query->orderByDesc('id'),
        };

        $paginator = $query->paginate($request->integer('per_page', 12));

        return response()->json($paginator);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'brand',
                'category',
                'images',
                'variants' => fn ($q) => $q->where('is_active', true),
                'reviews' => fn ($q) => $q->where('is_approved', true)->with('user:id,name'),
            ])
            ->firstOrFail();

        $related = Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images', 'variants'])
            ->take(4)
            ->get();

        return response()->json([
            'data' => $product,
            'related' => $related,
        ]);
    }
}
