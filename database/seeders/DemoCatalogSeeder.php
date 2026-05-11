<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $women = Category::query()->firstOrCreate(
            ['slug' => 'women'],
            ['name' => 'Women', 'description' => 'Women\'s fragrances', 'is_active' => true, 'sort_order' => 1]
        );
        Category::query()->firstOrCreate(
            ['slug' => 'men'],
            ['name' => 'Men', 'description' => 'Men\'s fragrances', 'is_active' => true, 'sort_order' => 2]
        );

        $brand = Brand::query()->firstOrCreate(
            ['slug' => 'noir-atelier'],
            ['name' => 'Noir Atelier', 'description' => 'House of bold contrasts.', 'is_active' => true, 'sort_order' => 1]
        );

        Banner::query()->firstOrCreate(
            ['title' => 'Midnight Bloom Collection'],
            [
                'subtitle' => 'Velvet florals wrapped in smoked amber.',
                'image_path' => 'demo/banner-1.jpg',
                'link_url' => '/shop',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Coupon::query()->firstOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percent',
                'value' => 10,
                'min_order_total' => 50,
                'max_uses' => 1000,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addYear(),
                'is_active' => true,
            ]
        );

        $product = Product::query()->firstOrCreate(
            ['slug' => 'noir-velvet-oud'],
            [
                'brand_id' => $brand->id,
                'category_id' => $women->id,
                'name' => 'Noir Velvet Oud',
                'sku' => 'NVO-100',
                'short_description' => 'A dark floral oud with saffron silk and rose absolue.',
                'description' => 'Designed for evening wear. Long-lasting extrait concentration.',
                'fragrance_type' => 'Oriental Floral',
                'gender' => 'women',
                'base_price' => 189.00,
                'compare_at_price' => 220.00,
                'is_featured' => true,
                'is_new' => true,
                'is_bestseller' => true,
                'is_on_sale' => true,
                'meta_title' => 'Noir Velvet Oud — Luxury Perfume',
                'meta_description' => 'Shop Noir Velvet Oud, an oriental floral oud extrait.',
                'is_active' => true,
            ]
        );

        ProductVariant::query()->firstOrCreate(
            ['sku' => 'NVO-100-50'],
            [
                'product_id' => $product->id,
                'name' => '50 ml',
                'size_label' => '50 ml',
                'price' => 189.00,
                'stock_quantity' => 120,
                'low_stock_threshold' => 8,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );

        ProductImage::query()->firstOrCreate(
            ['product_id' => $product->id, 'path' => 'demo/noir-velvet-oud.jpg'],
            ['sort_order' => 0, 'is_primary' => true]
        );

        $product2 = Product::query()->firstOrCreate(
            ['slug' => 'gold-saffron-skin'],
            [
                'brand_id' => $brand->id,
                'category_id' => $women->id,
                'name' => 'Gold Saffron Skin',
                'sku' => 'GSS-100',
                'short_description' => 'Skin-close musk with honeyed saffron threads.',
                'description' => 'Minimalist luxury. Unisex leaning feminine.',
                'fragrance_type' => 'Amber Musk',
                'gender' => 'unisex',
                'base_price' => 145.00,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_on_sale' => false,
                'is_active' => true,
            ]
        );

        ProductVariant::query()->firstOrCreate(
            ['sku' => 'GSS-100-50'],
            [
                'product_id' => $product2->id,
                'name' => '50 ml',
                'size_label' => '50 ml',
                'price' => 145.00,
                'stock_quantity' => 80,
                'low_stock_threshold' => 5,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );
    }
}
