<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing products and banners if any (optional, but good for fresh state)
        // Product::truncate();
        // Banner::truncate();

        // 2. Ensure Banners
        $bannerData = [
            [
                'title' => 'Premium Electronics Collection',
                'subtitle' => 'Get the latest gadgets at the best prices in Bangladesh.',
                'image' => 'banners/hero_banner_1.png',
                'link' => '/category/electronics',
                'button_text' => 'Shop Now',
                'badge_text' => 'OFFER',
                'type' => 'hero',
                'sort_order' => 1,
            ],
            [
                'title' => 'Trending Fashion 2024',
                'subtitle' => 'Discover the best style for your daily life.',
                'image' => 'banners/hero_banner_2.png',
                'link' => '/category/fashion',
                'button_text' => 'Explore',
                'badge_text' => 'NEW',
                'type' => 'hero',
                'sort_order' => 2,
            ],
            [
                'title' => 'Ultimate Gaming Gear',
                'subtitle' => 'Level up your gaming experience with top-tier peripherals.',
                'image' => 'banners/hero_banner_3.png',
                'link' => '/category/electronics',
                'button_text' => 'Shop Gaming',
                'badge_text' => 'GAMING',
                'type' => 'hero',
                'sort_order' => 3,
            ],
        ];

        foreach ($bannerData as $data) {
            Banner::updateOrCreate(['title' => $data['title']], array_merge($data, ['is_active' => true]));
        }

        // 3. Define 10 Real Products
        $products = [
            [
                'name' => 'iPhone 15 Pro - Natural Titanium',
                'category' => 'electronics',
                'subcategory' => 'Smartphones',
                'price' => 145000,
                'sale_price' => 138000,
                'brand' => 'Apple',
                'image' => 'products/iphone15pro.png',
                'desc' => 'The iPhone 15 Pro features a strong and light aerospace-grade titanium design. It also has a 48MP Main camera for super-high-resolution photos and the A17 Pro chip for next-level gaming performance.',
            ],
            [
                'name' => 'Sony WH-1000XM5 Wireless Headphones',
                'category' => 'electronics',
                'subcategory' => 'Headphones',
                'price' => 35000,
                'sale_price' => 32000,
                'brand' => 'Sony',
                'image' => 'products/sony_wh1000xm5.png',
                'desc' => 'With two processors controlling eight microphones, these headphones feature industry-leading noise cancellation and exceptional call quality.',
            ],
            [
                'name' => 'MacBook Pro M3 14-inch',
                'category' => 'electronics',
                'subcategory' => 'Laptops',
                'price' => 210000,
                'sale_price' => 195000,
                'brand' => 'Apple',
                'image' => 'products/macbook_m3.png',
                'desc' => 'The M3 chip brings even greater capabilities to the 14-inch MacBook Pro. With up to 18 hours of battery life and a beautiful Liquid Retina XDR display, it\'s a pro laptop like no other.',
            ],
            [
                'name' => 'Nike Air Jordan 1 Retro High OG',
                'category' => 'fashion',
                'subcategory' => 'Shoes',
                'price' => 18000,
                'sale_price' => 16500,
                'brand' => 'Nike',
                'image' => 'products/nike_jordans.png',
                'desc' => 'Familiar but always fresh, the iconic Air Jordan 1 is remastered for today\'s sneakerhead culture. This Retro High OG version features premium leather and comfortable cushioning.',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category' => 'electronics',
                'subcategory' => 'Smartphones',
                'price' => 135000,
                'sale_price' => 125000,
                'brand' => 'Samsung',
                'image' => 'products/samsung_s24_ultra.png',
                'desc' => 'Galaxy S24 Ultra, the ultimate form of Galaxy Ultra with a new titanium exterior and a 6.8-inch flat display. It\'s an absolute marvel of design.',
            ],
            [
                'name' => 'Adidas Ultraboost Light',
                'category' => 'fashion',
                'subcategory' => 'Shoes',
                'price' => 15000,
                'sale_price' => 13500,
                'brand' => 'Adidas',
                'image' => 'products/adidas_ultraboost.png',
                'desc' => 'Experience epic energy with the new Ultraboost Light, our lightest Ultraboost ever. The magic lies in the Light BOOST midsole, a new generation of Adidas BOOST.',
            ],
            [
                'name' => 'Logitech G Pro X Superlight 2',
                'category' => 'electronics',
                'subcategory' => 'Headphones', // Or find/create Gaming category
                'price' => 16500,
                'sale_price' => 15000,
                'brand' => 'Logitech',
                'image' => 'products/logitech_mouse.png',
                'desc' => 'The successor to the championship-winning gaming mouse is now faster and more precise. Lightweight design at 60g with HERO 2 sensor.',
            ],
            [
                'name' => 'Nintendo Switch OLED Model',
                'category' => 'electronics',
                'subcategory' => 'Smart TV', // Closest placeholder
                'price' => 38000,
                'sale_price' => 35000,
                'brand' => 'Nintendo',
                'image' => 'products/nintendo_switch.png',
                'desc' => 'Enjoy your favorite games anytime, anywhere, with a vibrant 7-inch OLED screen that makes colors pop.',
            ],
            [
                'name' => 'Razer DeathAdder V3 Pro',
                'category' => 'electronics',
                'subcategory' => 'Headphones', // Closest placeholder
                'price' => 14000,
                'sale_price' => 12500,
                'brand' => 'Razer',
                'image' => 'products/razer_mouse.png',
                'desc' => 'Victory takes on a new shape with the Razer DeathAdder V3 Pro. Its iconic ergonomic form is now 25% lighter than its predecessor.',
            ],
            [
                'name' => 'Canon EOS R5 Mirrorless Camera',
                'category' => 'electronics',
                'subcategory' => 'Cameras',
                'price' => 350000,
                'sale_price' => 320000,
                'brand' => 'Canon',
                'image' => 'products/canon_r5.png',
                'desc' => 'The EOS R5 is designed for the professional photographer and filmmaker. Featuring a 45MP sensor and 8K video recording.',
            ],
        ];

        foreach ($products as $i => $p) {
            $category = Category::where('slug', $p['category'])->first();
            if (!$category) continue;

            $subcategory = Subcategory::where('name', $p['subcategory'])->where('category_id', $category->id)->first();
            
            $product = Product::create([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory?->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['desc'],
                'short_description' => substr($p['desc'], 0, 100) . '...',
                'price' => $p['price'],
                'sale_price' => $p['sale_price'],
                'stock' => 50,
                'sku' => 'REAL-' . strtoupper(Str::random(8)),
                'thumbnail' => $p['image'],
                'rating' => 4.9,
                'review_count' => rand(10, 50),
                'is_featured' => true,
                'is_active' => true,
                'is_new' => true,
                'brand' => $p['brand'],
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $p['image'],
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }
    }
}
