<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        Category::query()->whereIn('slug', ['women', 'men'])->update(['is_active' => false]);

        $categories = [
            ['slug' => 'men-perfumes', 'name' => 'Men Perfumes', 'sort' => 1],
            ['slug' => 'women-perfumes', 'name' => 'Women Perfumes', 'sort' => 2],
            ['slug' => 'unisex', 'name' => 'Unisex', 'sort' => 3],
            ['slug' => 'attars', 'name' => 'Attars', 'sort' => 4],
            ['slug' => 'oud', 'name' => 'Oud', 'sort' => 5],
            ['slug' => 'arabic-collection', 'name' => 'Arabic Collection', 'sort' => 6],
            ['slug' => 'luxury-collection', 'name' => 'Luxury Collection', 'sort' => 7],
        ];

        $catModels = [];
        foreach ($categories as $c) {
            $catModels[$c['slug']] = Category::query()->firstOrCreate(
                ['slug' => $c['slug']],
                [
                    'name' => $c['name'],
                    'description' => $c['name'].' — curated selection.',
                    'is_active' => true,
                    'sort_order' => $c['sort'],
                ]
            );
        }

        $brand = Brand::query()->firstOrCreate(
            ['slug' => 'noir-atelier'],
            ['name' => 'Noir Atelier', 'description' => 'House of bold contrasts.', 'is_active' => true, 'sort_order' => 1]
        );

        $banners = [
            ['title' => 'Velvet Oud Nocturne', 'subtitle' => 'Smoked rose, saffron silk, and aged Cambodian oud.', 'sort' => 1, 'seed' => 'hero-a'],
            ['title' => 'Attar of the Majlis', 'subtitle' => 'Pure oil parfums — one drop, twelve hours.', 'sort' => 2, 'seed' => 'hero-b'],
            ['title' => 'Limited: Gold Thread', 'subtitle' => 'Twelve hundred bottles worldwide.', 'sort' => 3, 'seed' => 'hero-c'],
        ];
        foreach ($banners as $b) {
            Banner::query()->firstOrCreate(
                ['title' => $b['title']],
                [
                    'subtitle' => $b['subtitle'],
                    'image_path' => 'demo/'.$b['seed'].'.jpg',
                    'link_url' => '/shop',
                    'sort_order' => $b['sort'],
                    'is_active' => true,
                ]
            );
        }

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

        $testimonials = [
            ['name' => 'Amira Al-Fayed', 'location' => 'Dubai', 'quote' => 'The attar lasts through evening majlis and beyond — true Gulf luxury without noise.', 'rating' => 5, 'sort' => 1],
            ['name' => 'James Laurent', 'location' => 'Paris', 'quote' => 'Finally an oud that feels Parisian at heart — refined, never overpowering.', 'rating' => 5, 'sort' => 2],
            ['name' => 'Sofia Mendes', 'location' => 'São Paulo', 'quote' => 'Packaging alone is objet d’art. The juice matches — velvet, gold, memory.', 'rating' => 5, 'sort' => 3],
        ];
        foreach ($testimonials as $t) {
            Testimonial::query()->firstOrCreate(
                ['name' => $t['name'], 'quote' => $t['quote']],
                [
                    'location' => $t['location'],
                    'rating' => $t['rating'],
                    'sort_order' => $t['sort'],
                    'is_active' => true,
                ]
            );
        }

        $oud = $catModels['oud'];
        $women = $catModels['women-perfumes'];
        $arabic = $catModels['arabic-collection'];
        $attars = $catModels['attars'];
        $luxury = $catModels['luxury-collection'];

        $product = Product::query()->updateOrCreate(
            ['slug' => 'noir-velvet-oud'],
            [
                'brand_id' => $brand->id,
                'category_id' => $oud->id,
                'name' => 'Noir Velvet Oud',
                'sku' => 'NVO-100',
                'short_description' => 'A dark floral oud with saffron silk and rose absolue.',
                'description' => 'Designed for evening wear. Extrait concentration with slow-released amber woods and a whisper of incense.',
                'top_notes' => 'Saffron, bergamot, pink pepper',
                'middle_notes' => 'Turkish rose, jasmine sambac, osmanthus',
                'base_notes' => 'Agarwood, patchouli, vanilla resin, musk',
                'fragrance_type' => 'Oriental Floral Oud',
                'gender' => 'women',
                'product_type' => 'oud',
                'base_price' => 189.00,
                'compare_at_price' => 220.00,
                'is_featured' => true,
                'is_new' => true,
                'is_bestseller' => true,
                'is_on_sale' => true,
                'is_limited_edition' => false,
                'meta_title' => 'Noir Velvet Oud — Luxury Perfume',
                'meta_description' => 'Shop Noir Velvet Oud, an oriental floral oud extrait.',
                'is_active' => true,
            ]
        );

        ProductVariant::query()->updateOrCreate(
            ['sku' => 'NVO-100-50'],
            [
                'product_id' => $product->id,
                'name' => '50 ml Extrait',
                'size_label' => '50 ml',
                'volume_ml' => 50,
                'price' => 189.00,
                'stock_quantity' => 120,
                'low_stock_threshold' => 8,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );
        ProductVariant::query()->updateOrCreate(
            ['sku' => 'NVO-100-100'],
            [
                'product_id' => $product->id,
                'name' => '100 ml Extrait',
                'size_label' => '100 ml',
                'volume_ml' => 100,
                'price' => 289.00,
                'stock_quantity' => 40,
                'low_stock_threshold' => 5,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );

        ProductImage::query()->firstOrCreate(
            ['product_id' => $product->id, 'path' => 'demo/noir-velvet-oud.jpg'],
            ['sort_order' => 0, 'is_primary' => true]
        );
        ProductImage::query()->firstOrCreate(
            ['product_id' => $product->id, 'path' => 'demo/noir-velvet-oud-2.jpg'],
            ['sort_order' => 1, 'is_primary' => false]
        );

        $product2 = Product::query()->updateOrCreate(
            ['slug' => 'gold-saffron-skin'],
            [
                'brand_id' => $brand->id,
                'category_id' => $arabic->id,
                'name' => 'Gold Saffron Skin',
                'sku' => 'GSS-100',
                'short_description' => 'Skin-close musk with honeyed saffron threads.',
                'description' => 'Minimalist luxury. Unisex leaning feminine. Inspired by souk dawn and silk abayas.',
                'top_notes' => 'Saffron, white tea, pear nectar',
                'middle_notes' => 'Amber accord, iris butter',
                'base_notes' => 'White musk, sandalwood, soft leather',
                'fragrance_type' => 'Amber Musk',
                'gender' => 'unisex',
                'product_type' => 'arabic_collection',
                'base_price' => 145.00,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_on_sale' => false,
                'is_limited_edition' => false,
                'is_active' => true,
            ]
        );

        ProductVariant::query()->updateOrCreate(
            ['sku' => 'GSS-100-50'],
            [
                'product_id' => $product2->id,
                'name' => '50 ml',
                'size_label' => '50 ml',
                'volume_ml' => 50,
                'price' => 145.00,
                'stock_quantity' => 80,
                'low_stock_threshold' => 5,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );

        $attar = Product::query()->updateOrCreate(
            ['slug' => 'mukhallat-royal-attar'],
            [
                'brand_id' => $brand->id,
                'category_id' => $attars->id,
                'name' => 'Mukhallat Royal Attar',
                'sku' => 'MRA-12',
                'short_description' => 'Dense oil blend — rose de mai, aged oud chips, and royal green frankincense.',
                'description' => 'Hand-compounded in small copper vessels. Apply sparingly to pulse points; develops over hours with body heat.',
                'top_notes' => 'Green frankincense, cardamom CO2',
                'middle_notes' => 'Rose de mai absolute, Taif rose',
                'base_notes' => 'Hindi oud, benzoin, castoreum (trace)',
                'fragrance_type' => 'Oriental Attar',
                'gender' => 'unisex',
                'product_type' => 'attar',
                'base_price' => 265.00,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new' => true,
                'is_on_sale' => false,
                'is_limited_edition' => false,
                'is_active' => true,
            ]
        );
        ProductVariant::query()->updateOrCreate(
            ['sku' => 'MRA-12-12'],
            [
                'product_id' => $attar->id,
                'name' => '12 ml oil',
                'size_label' => '12 ml',
                'volume_ml' => 12,
                'price' => 265.00,
                'stock_quantity' => 200,
                'low_stock_threshold' => 15,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );
        ProductImage::query()->firstOrCreate(
            ['product_id' => $attar->id, 'path' => 'demo/mukhallat-attar.jpg'],
            ['sort_order' => 0, 'is_primary' => true]
        );

        $limited = Product::query()->updateOrCreate(
            ['slug' => 'gold-thread-extrait'],
            [
                'brand_id' => $brand->id,
                'category_id' => $luxury->id,
                'name' => 'Gold Thread Extrait',
                'sku' => 'GTE-50',
                'short_description' => 'Limited to 1,200 bottles — gold-leaf infusion in honeyed osmanthus.',
                'description' => 'Each bottle is numbered and wax-sealed. A collectors’ piece for the most precious evenings.',
                'top_notes' => 'Osmanthus, neroli, aldehydes',
                'middle_notes' => 'Honey accord, iris pallida',
                'base_notes' => 'Beeswax absolute, vetiver, soft oud',
                'fragrance_type' => 'Floral Chypre',
                'gender' => 'women',
                'product_type' => 'luxury_collection',
                'base_price' => 420.00,
                'compare_at_price' => 480.00,
                'is_featured' => true,
                'is_new' => true,
                'is_bestseller' => false,
                'is_on_sale' => false,
                'is_limited_edition' => true,
                'is_active' => true,
            ]
        );
        ProductVariant::query()->updateOrCreate(
            ['sku' => 'GTE-50-50'],
            [
                'product_id' => $limited->id,
                'name' => '50 ml numbered',
                'size_label' => '50 ml',
                'volume_ml' => 50,
                'price' => 420.00,
                'stock_quantity' => 85,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );
        ProductImage::query()->firstOrCreate(
            ['product_id' => $limited->id, 'path' => 'demo/gold-thread.jpg'],
            ['sort_order' => 0, 'is_primary' => true]
        );

        $menProduct = Product::query()->updateOrCreate(
            ['slug' => 'desert-king-edp'],
            [
                'brand_id' => $brand->id,
                'category_id' => $catModels['men-perfumes']->id,
                'name' => 'Desert King',
                'sku' => 'DKE-100',
                'short_description' => 'Incense smoke over black tea and leather saddles.',
                'description' => 'For those who command silence before they speak. Dry woods and a ribbon of oud smoke.',
                'top_notes' => 'Black tea, bergamot, incense',
                'middle_notes' => 'Leather, cedarwood, saffron',
                'base_notes' => 'Oud smoke, vetiver, musk',
                'fragrance_type' => 'Woody Leather',
                'gender' => 'men',
                'product_type' => 'perfume',
                'base_price' => 168.00,
                'is_featured' => false,
                'is_bestseller' => true,
                'is_new' => true,
                'is_on_sale' => false,
                'is_limited_edition' => false,
                'is_active' => true,
            ]
        );
        ProductVariant::query()->updateOrCreate(
            ['sku' => 'DKE-100-100'],
            [
                'product_id' => $menProduct->id,
                'name' => '100 ml',
                'size_label' => '100 ml',
                'volume_ml' => 100,
                'price' => 168.00,
                'stock_quantity' => 95,
                'low_stock_threshold' => 6,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );
        ProductImage::query()->firstOrCreate(
            ['product_id' => $menProduct->id, 'path' => 'demo/desert-king.jpg'],
            ['sort_order' => 0, 'is_primary' => true]
        );

        $unisex = Product::query()->updateOrCreate(
            ['slug' => 'white-musk-veil'],
            [
                'brand_id' => $brand->id,
                'category_id' => $catModels['unisex']->id,
                'name' => 'White Musk Veil',
                'sku' => 'WMV-75',
                'short_description' => 'Sheer musk and aldehydic lift — second-skin minimalism.',
                'description' => 'A quiet signature for day and night. Layer-friendly with any attar or oud base.',
                'top_notes' => 'Aldehydes, pear water',
                'middle_notes' => 'White musk, cashmeran',
                'base_notes' => 'Ambrette seed, soft woods',
                'fragrance_type' => 'Clean Musk',
                'gender' => 'unisex',
                'product_type' => 'perfume',
                'base_price' => 118.00,
                'is_featured' => false,
                'is_bestseller' => true,
                'is_new' => false,
                'is_on_sale' => true,
                'is_limited_edition' => false,
                'is_active' => true,
            ]
        );
        ProductVariant::query()->updateOrCreate(
            ['sku' => 'WMV-75-75'],
            [
                'product_id' => $unisex->id,
                'name' => '75 ml',
                'size_label' => '75 ml',
                'volume_ml' => 75,
                'price' => 98.00,
                'stock_quantity' => 150,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
                'is_active' => true,
            ]
        );
        ProductImage::query()->firstOrCreate(
            ['product_id' => $unisex->id, 'path' => 'demo/white-musk.jpg'],
            ['sort_order' => 0, 'is_primary' => true]
        );
    }
}
