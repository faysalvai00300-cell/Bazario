<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Banner;
use App\Models\PromoCode;
use App\Models\FlashSale;
use App\Models\Review;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Bazario Admin',
            'email' => 'admin@nackobd.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Test Customer
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@nackobd.com',
            'email_verified_at' => now(),
            'password' => Hash::make('customer123'),
            'role' => 'customer',
        ]);

        // ============ CATEGORIES ============
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'icon' => '💻', 'color' => '#3B82F6', 'description' => 'Latest electronics and gadgets'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'icon' => '👗', 'color' => '#EC4899', 'description' => 'Trendy clothing and accessories'],
            ['name' => 'Home & Living', 'slug' => 'home-living', 'icon' => '🏠', 'color' => '#10B981', 'description' => 'Home furniture and decor'],
            ['name' => 'Sports & Outdoors', 'slug' => 'sports', 'icon' => '⚽', 'color' => '#F59E0B', 'description' => 'Sports equipment and gear'],
            ['name' => 'Beauty & Health', 'slug' => 'beauty-health', 'icon' => '💄', 'color' => '#8B5CF6', 'description' => 'Beauty and personal care'],
            ['name' => 'Books & Stationery', 'slug' => 'books', 'icon' => '📚', 'color' => '#14B8A6', 'description' => 'Books, pens and stationery'],
            ['name' => 'Toys & Games', 'slug' => 'toys-games', 'icon' => '🎮', 'color' => '#EF4444', 'description' => 'Toys for all ages'],
            ['name' => 'Grocery & Food', 'slug' => 'grocery-food', 'icon' => '🛒', 'color' => '#22C55E', 'description' => 'Fresh groceries and food items'],
        ];

        $createdCategories = [];
        foreach ($categories as $i => $cat) {
            $createdCategories[] = Category::create(array_merge($cat, ['sort_order' => $i + 1]));
        }

        // ============ SUBCATEGORIES ============
        $subcategoryData = [
            'electronics' => ['Smartphones', 'Laptops', 'Smart TV', 'Headphones', 'Cameras'],
            'fashion' => ['Men\'s Clothing', 'Women\'s Clothing', 'Shoes', 'Bags & Wallets'],
            'home-living' => ['Furniture', 'Kitchenware', 'Bedding'],
            'sports' => ['Fitness Equipment', 'Outdoor Gear', 'Sportswear'],
            'beauty-health' => ['Skincare', 'Haircare', 'Supplements'],
            'books' => ['Novels', 'Educational'],
            'toys-games' => ['Action Figures', 'Board Games'],
            'grocery-food' => ['Snacks & Beverages'],
        ];

        $subMap = [];
        foreach ($createdCategories as $cat) {
            $subs = $subcategoryData[$cat->slug] ?? [];
            foreach ($subs as $subName) {
                $sub = Subcategory::create([
                    'category_id' => $cat->id,
                    'name' => $subName,
                    'slug' => Str::slug($subName . '-' . $cat->slug),
                ]);
                $subMap[$cat->slug][] = $sub;
            }
        }

        // ============ PRODUCTS ============
        $productData = [
            // Electronics - Smartphones
            ['cat' => 'electronics', 'sub' => 0, 'name' => 'Samsung Galaxy S24 Ultra', 'price' => 129999, 'sale_price' => 109999, 'brand' => 'Samsung', 'rating' => 4.8, 'reviews' => 234, 'is_featured' => true, 'is_new' => true, 'stock' => 45, 'desc' => 'The ultimate Galaxy experience with 200MP camera, S Pen, and Snapdragon 8 Gen 3.'],
            ['cat' => 'electronics', 'sub' => 0, 'name' => 'iPhone 15 Pro Max', 'price' => 149999, 'sale_price' => 139999, 'brand' => 'Apple', 'rating' => 4.9, 'reviews' => 512, 'is_featured' => true, 'stock' => 30, 'desc' => 'Titanium design with A17 Pro chip, 48MP main camera system, and USB-C connectivity.'],
            ['cat' => 'electronics', 'sub' => 0, 'name' => 'Xiaomi 14 Pro', 'price' => 89999, 'sale_price' => 74999, 'brand' => 'Xiaomi', 'rating' => 4.5, 'reviews' => 156, 'stock' => 60, 'desc' => 'Leica professional camera, Snapdragon 8 Gen 3, and 120W HyperCharge technology.'],
            ['cat' => 'electronics', 'sub' => 0, 'name' => 'Google Pixel 8 Pro', 'price' => 99999, 'sale_price' => 84999, 'brand' => 'Google', 'rating' => 4.6, 'reviews' => 89, 'stock' => 25, 'desc' => 'AI-powered camera features with Google Tensor G3 chip and 7 years of updates.'],
            ['cat' => 'electronics', 'sub' => 0, 'name' => 'OnePlus 12', 'price' => 79999, 'sale_price' => 69999, 'brand' => 'OnePlus', 'rating' => 4.4, 'reviews' => 120, 'stock' => 50, 'desc' => 'Hasselblad tuned cameras, 100W SUPERVOOC charging, and Snapdragon 8 Gen 3.'],
            // Electronics - Laptops
            ['cat' => 'electronics', 'sub' => 1, 'name' => 'MacBook Pro 14" M3', 'price' => 199999, 'sale_price' => 189999, 'brand' => 'Apple', 'rating' => 4.9, 'reviews' => 345, 'is_featured' => true, 'stock' => 20, 'desc' => 'M3 chip with hardware-accelerated ray tracing and 18-hour battery life.'],
            ['cat' => 'electronics', 'sub' => 1, 'name' => 'Dell XPS 15 OLED', 'price' => 159999, 'sale_price' => 139999, 'brand' => 'Dell', 'rating' => 4.7, 'reviews' => 178, 'stock' => 15, 'desc' => 'OLED display with Intel Core i9, RTX 4070, and premium craftsmanship.'],
            ['cat' => 'electronics', 'sub' => 1, 'name' => 'Asus ROG Zephyrus G16', 'price' => 179999, 'sale_price' => null, 'brand' => 'Asus', 'rating' => 4.6, 'reviews' => 92, 'stock' => 18, 'desc' => 'Gaming powerhouse with RTX 4080, 240Hz display, and AMD Ryzen 9.'],
            // Electronics - Headphones
            ['cat' => 'electronics', 'sub' => 3, 'name' => 'Sony WH-1000XM5', 'price' => 34999, 'sale_price' => 27999, 'brand' => 'Sony', 'rating' => 4.8, 'reviews' => 567, 'is_featured' => true, 'stock' => 80, 'desc' => 'Industry-leading noise cancellation with 30-hour battery and multi-device pairing.'],
            ['cat' => 'electronics', 'sub' => 3, 'name' => 'Apple AirPods Pro 2', 'price' => 29999, 'sale_price' => 25999, 'brand' => 'Apple', 'rating' => 4.7, 'reviews' => 312, 'stock' => 100, 'desc' => 'Adaptive Audio, Personalized Spatial Audio, and up to 30 hours total listening time.'],
            // Fashion - Men
            ['cat' => 'fashion', 'sub' => 0, 'name' => 'Premium Slim Fit Suit', 'price' => 15999, 'sale_price' => 11999, 'brand' => 'Zara', 'rating' => 4.5, 'reviews' => 78, 'is_featured' => true, 'stock' => 35, 'desc' => 'Elegant slim-fit suit perfect for formal occasions, available in multiple colors.'],
            ['cat' => 'fashion', 'sub' => 0, 'name' => 'Casual Linen Shirt', 'price' => 2999, 'sale_price' => 1999, 'brand' => 'H&M', 'rating' => 4.3, 'reviews' => 145, 'stock' => 200, 'desc' => 'Breathable linen fabric perfect for summer, available in 8 colors.'],
            ['cat' => 'fashion', 'sub' => 0, 'name' => 'Denim Jacket Classic', 'price' => 4999, 'sale_price' => 3499, 'brand' => 'Levi\'s', 'rating' => 4.6, 'reviews' => 98, 'stock' => 75, 'desc' => 'Classic denim jacket with modern fit and durable construction.'],
            // Fashion - Women
            ['cat' => 'fashion', 'sub' => 1, 'name' => 'Floral Maxi Dress', 'price' => 3999, 'sale_price' => 2799, 'brand' => 'Mango', 'rating' => 4.7, 'reviews' => 234, 'is_featured' => true, 'stock' => 150, 'desc' => 'Beautiful floral print maxi dress perfect for summer outings.'],
            ['cat' => 'fashion', 'sub' => 1, 'name' => 'Women\'s Blazer Set', 'price' => 7999, 'sale_price' => 5999, 'brand' => 'Zara', 'rating' => 4.5, 'reviews' => 89, 'stock' => 60, 'desc' => 'Professional blazer set for the modern working woman.'],
            // Fashion - Shoes
            ['cat' => 'fashion', 'sub' => 2, 'name' => 'Nike Air Max 270', 'price' => 12999, 'sale_price' => 9999, 'brand' => 'Nike', 'rating' => 4.7, 'reviews' => 423, 'is_featured' => true, 'stock' => 90, 'desc' => 'Air-cushioned comfort with Max Air unit for all-day wearability.'],
            ['cat' => 'fashion', 'sub' => 2, 'name' => 'Adidas Ultraboost 23', 'price' => 14999, 'sale_price' => 11999, 'brand' => 'Adidas', 'rating' => 4.6, 'reviews' => 256, 'stock' => 70, 'desc' => 'Energy-returning Boost midsole for ultimate running performance.'],
            // Home & Living
            ['cat' => 'home-living', 'sub' => 0, 'name' => 'L-Shaped Sofa Set', 'price' => 49999, 'sale_price' => 39999, 'brand' => 'IKEA', 'rating' => 4.6, 'reviews' => 67, 'is_featured' => true, 'stock' => 12, 'desc' => 'Premium fabric L-shaped sofa with memory foam cushions.'],
            ['cat' => 'home-living', 'sub' => 1, 'name' => 'Premium Cookware Set 15pcs', 'price' => 12999, 'sale_price' => 8999, 'brand' => 'Prestige', 'rating' => 4.5, 'reviews' => 134, 'stock' => 45, 'desc' => 'Non-stick stainless steel cookware set for professional cooking at home.'],
            ['cat' => 'home-living', 'sub' => 2, 'name' => 'Egyptian Cotton Bedsheet Set', 'price' => 4999, 'sale_price' => 3499, 'brand' => 'Sleepwell', 'rating' => 4.7, 'reviews' => 189, 'stock' => 100, 'desc' => '100% Egyptian cotton 800 thread count bedsheet set with 4 pillowcases.'],
            // Sports
            ['cat' => 'sports', 'sub' => 0, 'name' => 'Fitness Treadmill Pro X', 'price' => 79999, 'sale_price' => 59999, 'brand' => 'ProForm', 'rating' => 4.4, 'reviews' => 45, 'is_featured' => true, 'stock' => 8, 'desc' => 'Commercial-grade treadmill with 20% incline, 22" touchscreen, and iFit membership.'],
            ['cat' => 'sports', 'sub' => 0, 'name' => 'Adjustable Dumbbell Set 32kg', 'price' => 14999, 'sale_price' => 11999, 'brand' => 'Bowflex', 'rating' => 4.8, 'reviews' => 312, 'stock' => 30, 'desc' => 'Space-saving adjustable dumbbells that replace 15 sets of weights.'],
            ['cat' => 'sports', 'sub' => 2, 'name' => 'Jogging Track Suit', 'price' => 3499, 'sale_price' => 2499, 'brand' => 'Puma', 'rating' => 4.4, 'reviews' => 156, 'stock' => 120, 'desc' => 'Lightweight moisture-wicking tracksuit for running and outdoor sports.'],
            // Beauty & Health
            ['cat' => 'beauty-health', 'sub' => 0, 'name' => 'Vitamin C Serum 30ml', 'price' => 1999, 'sale_price' => 1499, 'brand' => 'The Ordinary', 'rating' => 4.6, 'reviews' => 678, 'is_featured' => true, 'stock' => 300, 'desc' => 'High-strength Vitamin C serum for brightening and anti-aging.'],
            ['cat' => 'beauty-health', 'sub' => 0, 'name' => 'Moisturizing Sunscreen SPF 50', 'price' => 1299, 'sale_price' => 999, 'brand' => 'Neutrogena', 'rating' => 4.5, 'reviews' => 445, 'stock' => 250, 'desc' => 'Broad-spectrum SPF 50 sunscreen with moisturizing formula.'],
            ['cat' => 'beauty-health', 'sub' => 1, 'name' => 'Hair Growth Serum Kit', 'price' => 2499, 'sale_price' => 1799, 'brand' => 'OGX', 'rating' => 4.3, 'reviews' => 234, 'stock' => 180, 'desc' => 'Complete hair growth kit with biotin-enriched serum and scalp massager.'],
            // Books
            ['cat' => 'books', 'sub' => 0, 'name' => 'Atomic Habits by James Clear', 'price' => 799, 'sale_price' => 599, 'brand' => 'Avery', 'rating' => 4.9, 'reviews' => 1234, 'is_featured' => true, 'stock' => 500, 'desc' => 'An Easy & Proven Way to Build Good Habits & Break Bad Ones.'],
            ['cat' => 'books', 'sub' => 0, 'name' => 'The Psychology of Money', 'price' => 699, 'sale_price' => 499, 'brand' => 'Harriman House', 'rating' => 4.8, 'reviews' => 876, 'stock' => 400, 'desc' => 'Timeless lessons on wealth, greed, and happiness by Morgan Housel.'],
            // Toys
            ['cat' => 'toys-games', 'sub' => 0, 'name' => 'LEGO Technic Ferrari 488 GTE', 'price' => 12999, 'sale_price' => 9999, 'brand' => 'LEGO', 'rating' => 4.8, 'reviews' => 145, 'is_featured' => true, 'stock' => 40, 'desc' => '1677-piece LEGO Technic Ferrari model with working V8 engine.'],
            ['cat' => 'toys-games', 'sub' => 1, 'name' => 'Monopoly Classic Board Game', 'price' => 1999, 'sale_price' => 1499, 'brand' => 'Hasbro', 'rating' => 4.6, 'reviews' => 234, 'stock' => 200, 'desc' => 'The classic real estate trading game for the whole family.'],
            // Grocery
            ['cat' => 'grocery-food', 'sub' => 0, 'name' => 'Organic Green Tea 100 Bags', 'price' => 599, 'sale_price' => 449, 'brand' => 'Twinings', 'rating' => 4.5, 'reviews' => 345, 'stock' => 500, 'desc' => 'Premium organic green tea with antioxidants for healthy living.'],
            ['cat' => 'grocery-food', 'sub' => 0, 'name' => 'Mixed Nuts Premium Pack 1kg', 'price' => 1999, 'sale_price' => 1599, 'brand' => 'Happilo', 'rating' => 4.7, 'reviews' => 567, 'stock' => 200, 'desc' => 'Premium mix of cashews, almonds, walnuts, and pistachios.'],
            // More Electronics
            ['cat' => 'electronics', 'sub' => 2, 'name' => 'Samsung 65" QLED 4K TV', 'price' => 89999, 'sale_price' => 74999, 'brand' => 'Samsung', 'rating' => 4.7, 'reviews' => 234, 'is_featured' => true, 'stock' => 15, 'desc' => 'Quantum Dot technology with 4K resolution and built-in Alexa.'],
            ['cat' => 'electronics', 'sub' => 4, 'name' => 'Canon EOS R6 Mark II', 'price' => 219999, 'sale_price' => 199999, 'brand' => 'Canon', 'rating' => 4.8, 'reviews' => 89, 'stock' => 10, 'desc' => '40MP full-frame mirrorless camera with 6K RAW video and IBIS.'],
            // More Fashion
            ['cat' => 'fashion', 'sub' => 3, 'name' => 'Leather Tote Bag Premium', 'price' => 8999, 'sale_price' => 6499, 'brand' => 'Coach', 'rating' => 4.7, 'reviews' => 123, 'stock' => 50, 'desc' => 'Genuine leather tote bag with spacious interior and gold hardware.'],
            // More Beauty
            ['cat' => 'beauty-health', 'sub' => 2, 'name' => 'Whey Protein Chocolate 2kg', 'price' => 3999, 'sale_price' => 2999, 'brand' => 'Optimum Nutrition', 'rating' => 4.6, 'reviews' => 456, 'is_featured' => true, 'stock' => 150, 'desc' => '25g protein per serving, 5.5g BCAAs for muscle growth and recovery.'],
            // More Home
            ['cat' => 'home-living', 'sub' => 1, 'name' => 'Air Fryer 5.5L Digital', 'price' => 7999, 'sale_price' => 5999, 'brand' => 'Philips', 'rating' => 4.8, 'reviews' => 567, 'is_featured' => true, 'stock' => 60, 'desc' => 'Cook with 95% less fat using Rapid Air technology, 13 preset programs.'],
            // More Sports
            ['cat' => 'sports', 'sub' => 1, 'name' => 'Camping Tent 4-Person Waterproof', 'price' => 8999, 'sale_price' => 6999, 'brand' => 'Coleman', 'rating' => 4.5, 'reviews' => 156, 'stock' => 35, 'desc' => '4-season camping tent with waterproof fly and UV protection.'],
            // More Products
            ['cat' => 'electronics', 'sub' => 0, 'name' => 'Realme GT 5 Pro', 'price' => 54999, 'sale_price' => 44999, 'brand' => 'Realme', 'rating' => 4.3, 'reviews' => 87, 'stock' => 70, 'desc' => 'Snapdragon 8 Gen 3, 50MP periscope camera, 240W charging speed.'],
            ['cat' => 'fashion', 'sub' => 2, 'name' => 'Puma Suede Classic Sneakers', 'price' => 7999, 'sale_price' => 5499, 'brand' => 'Puma', 'rating' => 4.4, 'reviews' => 189, 'stock' => 85, 'desc' => 'Iconic suede sneakers with cushioned sole and classic design.'],
            ['cat' => 'home-living', 'sub' => 0, 'name' => 'Smart LED Desk Lamp USB', 'price' => 2499, 'sale_price' => 1799, 'brand' => 'Xiaomi', 'rating' => 4.5, 'reviews' => 234, 'stock' => 150, 'desc' => 'Smart LED desk lamp with adjustable color temperature and USB-C charging port.'],
            ['cat' => 'beauty-health', 'sub' => 0, 'name' => 'Facial Cleansing Set 5-in-1', 'price' => 1599, 'sale_price' => 1199, 'brand' => 'CeraVe', 'rating' => 4.7, 'reviews' => 312, 'stock' => 200, 'desc' => 'Complete facial cleansing system with gentle formula for all skin types.'],
            ['cat' => 'books', 'sub' => 1, 'name' => 'Python Crash Course 3rd Edition', 'price' => 1299, 'sale_price' => 999, 'brand' => 'No Starch Press', 'rating' => 4.8, 'reviews' => 567, 'stock' => 300, 'desc' => 'Hands-on, project-based introduction to programming with Python.'],
            ['cat' => 'toys-games', 'sub' => 0, 'name' => 'Hot Wheels Ultimate Garage', 'price' => 5999, 'sale_price' => 4499, 'brand' => 'Mattel', 'rating' => 4.6, 'reviews' => 178, 'stock' => 60, 'desc' => '5-floor garage with elevator, ramp, and 2 exclusive Die-Cast cars.'],
            ['cat' => 'grocery-food', 'sub' => 0, 'name' => 'Instant Oats 1kg Premium', 'price' => 299, 'sale_price' => null, 'brand' => 'Quaker', 'rating' => 4.6, 'reviews' => 456, 'stock' => 1000, 'desc' => '100% whole grain oats, no added sugar, perfect for healthy breakfast.'],
            ['cat' => 'electronics', 'sub' => 3, 'name' => 'JBL Charge 5 Bluetooth Speaker', 'price' => 14999, 'sale_price' => 11999, 'brand' => 'JBL', 'rating' => 4.7, 'reviews' => 445, 'is_featured' => true, 'stock' => 80, 'desc' => 'Waterproof portable speaker with 20-hour battery and power bank function.'],
            ['cat' => 'sports', 'sub' => 0, 'name' => 'Yoga Mat Premium Non-Slip', 'price' => 2499, 'sale_price' => 1799, 'brand' => 'Manduka', 'rating' => 4.7, 'reviews' => 289, 'stock' => 200, 'desc' => '6mm thick premium yoga mat with alignment lines and carrying strap.'],
            ['cat' => 'fashion', 'sub' => 1, 'name' => 'Women\'s Leather Handbag', 'price' => 5999, 'sale_price' => 3999, 'brand' => 'Fossil', 'rating' => 4.5, 'reviews' => 167, 'stock' => 70, 'desc' => 'Genuine leather handbag with multiple compartments and zipper closure.'],
            ['cat' => 'home-living', 'sub' => 1, 'name' => 'Espresso Coffee Machine', 'price' => 24999, 'sale_price' => 19999, 'brand' => 'DeLonghi', 'rating' => 4.8, 'reviews' => 234, 'is_featured' => true, 'stock' => 25, 'desc' => 'Fully automatic espresso machine with built-in grinder and milk frother.'],
        ];

        $createdProducts = [];
        foreach ($productData as $i => $p) {
            $catSlug = $p['cat'];
            $cat = Category::where('slug', $catSlug)->first();
            if (!$cat) continue;

            $subIndex = $p['sub'] ?? 0;
            $sub = $subMap[$catSlug][$subIndex] ?? null;

            $slug = Str::slug($p['name']) . '-' . ($i + 1);

            // Use picsum for realistic product images
            $imageSeeds = [
                'electronics' => [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                'fashion' => [200, 210, 220, 230, 240, 250, 260, 270, 280, 290],
                'home-living' => [300, 310, 320, 330, 340, 350],
                'sports' => [400, 410, 420, 430, 440],
                'beauty-health' => [500, 510, 520, 530, 540],
                'books' => [600, 610, 620, 630],
                'toys-games' => [700, 710, 720],
                'grocery-food' => [800, 810, 820],
            ];

            $seeds = $imageSeeds[$catSlug] ?? [100];
            $seed = $seeds[$i % count($seeds)];

            $product = Product::create([
                'category_id' => $cat->id,
                'subcategory_id' => $sub?->id,
                'name' => $p['name'],
                'slug' => $slug,
                'description' => $p['desc'] ?? 'Premium quality product with excellent features and durability.',
                'short_description' => substr($p['desc'] ?? '', 0, 100),
                'price' => $p['price'],
                'sale_price' => $p['sale_price'] ?? null,
                'stock' => $p['stock'] ?? 50,
                'sku' => 'FMO-' . strtoupper(Str::random(8)),
                'thumbnail' => "https://picsum.photos/seed/{$seed}/400/400",
                'rating' => $p['rating'] ?? 4.0,
                'review_count' => $p['reviews'] ?? 0,
                'is_featured' => $p['is_featured'] ?? false,
                'is_active' => true,
                'is_new' => $p['is_new'] ?? ($i < 10),
                'brand' => $p['brand'] ?? 'Bazario',
            ]);

            // Add product images
            ProductImage::create([
                'product_id' => $product->id,
                'image' => "https://picsum.photos/seed/{$seed}/600/600",
                'is_primary' => true,
                'sort_order' => 0,
            ]);
            $nextSeed = $seed + 1;
            ProductImage::create([
                'product_id' => $product->id,
                'image' => "https://picsum.photos/seed/{$nextSeed}/600/600",
                'is_primary' => false,
                'sort_order' => 1,
            ]);

            $createdProducts[] = $product;
        }

        // ============ FLASH SALES (10 products) ============
        $flashProducts = array_slice($createdProducts, 0, 10);
        foreach ($flashProducts as $product) {
            FlashSale::create([
                'product_id' => $product->id,
                'sale_price' => round($product->price * 0.60),
                'quantity' => rand(50, 200),
                'sold' => rand(10, 80),
                'starts_at' => now(),
                'ends_at' => now()->addHours(rand(6, 24)),
                'is_active' => true,
            ]);
        }

        // ============ BANNERS ============
        $banners = [
            [
                'title' => 'Big Electronics Sale',
                'subtitle' => 'Up to 50% OFF on Smartphones, Laptops & More!',
                'image' => 'https://picsum.photos/seed/banner1/1920/600',
                'link' => '/category/electronics',
                'button_text' => 'Shop Now',
                'badge_text' => '🔥 HOT DEALS',
                'type' => 'hero',
                'sort_order' => 1,
            ],
            [
                'title' => 'New Fashion Arrivals',
                'subtitle' => 'Discover the Latest Trends in Fashion & Style',
                'image' => 'https://picsum.photos/seed/banner2/1920/600',
                'link' => '/category/fashion',
                'button_text' => 'Explore Collection',
                'badge_text' => '✨ NEW ARRIVALS',
                'type' => 'hero',
                'sort_order' => 2,
            ],
            [
                'title' => 'Home & Living Deals',
                'subtitle' => 'Transform Your Home with Premium Furniture & Decor',
                'image' => 'https://picsum.photos/seed/banner3/1920/600',
                'link' => '/category/home-living',
                'button_text' => 'Shop Home',
                'badge_text' => '🏠 SPECIAL OFFER',
                'type' => 'promo',
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create(array_merge($banner, ['is_active' => true]));
        }

        // ============ PROMO CODES ============
        PromoCode::create(['code' => 'WELCOME10', 'type' => 'percentage', 'value' => 10, 'min_order' => 500, 'max_discount' => 1000, 'usage_limit' => 1000, 'expires_at' => now()->addMonths(6), 'is_active' => true]);
        PromoCode::create(['code' => 'SAVE20', 'type' => 'percentage', 'value' => 20, 'min_order' => 2000, 'max_discount' => 2000, 'usage_limit' => 500, 'expires_at' => now()->addMonths(3), 'is_active' => true]);
        PromoCode::create(['code' => 'FLASH30', 'type' => 'percentage', 'value' => 30, 'min_order' => 5000, 'max_discount' => 3000, 'usage_limit' => 200, 'expires_at' => now()->addMonth(), 'is_active' => true]);
        PromoCode::create(['code' => 'FLAT500', 'type' => 'fixed', 'value' => 500, 'min_order' => 3000, 'usage_limit' => null, 'expires_at' => now()->addMonths(12), 'is_active' => true]);

        // ============ REVIEWS ============
        $reviewTexts = [
            ['title' => 'Excellent Product!', 'body' => 'Absolutely love this product. Exceeded all my expectations. Fast delivery and great packaging. Will definitely buy again!', 'rating' => 5, 'name' => 'Ahmed Rahman'],
            ['title' => 'Great Value for Money', 'body' => 'Very good quality for the price. I was a bit skeptical at first but this product is genuinely impressive. Highly recommended!', 'rating' => 5, 'name' => 'Sarah Islam'],
            ['title' => 'Good Quality', 'body' => 'Solid build quality and works as described. Shipping was fast and the product was well packaged. Happy with my purchase.', 'rating' => 4, 'name' => 'Mohammad Ali'],
            ['title' => 'Worth Every Penny', 'body' => 'Premium feel and excellent performance. This is exactly what I was looking for. Customer service was also very helpful.', 'rating' => 5, 'name' => 'Fatima Khan'],
            ['title' => 'Pretty Good', 'body' => 'Nice product overall. Delivery was on time and the quality is decent. Minor packaging issues but the product itself is fine.', 'rating' => 4, 'name' => 'Rahim Sheikh'],
        ];

        $customer = User::where('role', 'customer')->first();
        foreach (array_slice($createdProducts, 0, 20) as $product) {
            $review = $reviewTexts[array_rand($reviewTexts)];
            Review::create([
                'product_id' => $product->id,
                'user_id' => $customer->id,
                'title' => $review['title'],
                'body' => $review['body'],
                'rating' => $review['rating'],
                'is_approved' => true,
                'reviewer_name' => $review['name'],
            ]);
        }
    }
}
