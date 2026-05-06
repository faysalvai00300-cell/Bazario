<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $heroBanners = Banner::where('is_active', true)->where('type', 'hero')->orderBy('sort_order')->get();
        $promoBanners = Banner::where('is_active', true)->where('type', 'promo')->orderBy('sort_order')->get();
        $banners = $heroBanners; // For backward compatibility if needed in some parts
        $categories = Category::where('is_active', true)->withCount('products')->orderBy('sort_order')->get();
        $page1Cats = Category::where('is_active', true)->where('target_page', 1)->with(['subcategories' => function($q) {
            $q->where('is_active', true)->withCount('products')->orderBy('sort_order');
        }])->get()->keyBy('target_box');
        $page2Cats = Category::where('is_active', true)->where('target_page', 2)->with(['products' => function($q) {
            $q->active()->featured()->latest()->take(8);
        }])->get()->keyBy('target_box');
        $page3Cats = Category::where('is_active', true)->where('target_page', 3)->with(['products' => function($q) {
            $q->active()->featured()->latest()->take(8);
        }])->get()->keyBy('target_box');
        $page4Cats = Category::where('is_active', true)->where('target_page', 4)->with(['products' => function($q) {
            $q->active()->featured()->latest()->take(10);
        }])->get()->keyBy('target_box');
        $page5Cats = Category::where('is_active', true)->where('target_page', 5)->get()->keyBy('target_box');

        $flashSales = FlashSale::where('is_active', true)
            ->where('ends_at', '>', now())
            ->with('product', 'product.images')
            ->take(12)
            ->get();
        $featuredProducts = Product::active()->featured()->latest()->with(['category', 'images'])->take(50)->get();
        $megaDeals = Product::active()->where('is_mega_deal', true)->latest()->with(['category', 'images'])->take(6)->get();
        $newArrivals = Product::active()->where('is_new', true)->latest()->with(['category', 'images'])->take(18)->get();
        $reviews = Review::where('is_approved', true)->with('product')->latest()->take(6)->get();

        return view('home', compact('heroBanners', 'promoBanners', 'banners', 'categories', 'flashSales', 'featuredProducts', 'megaDeals', 'newArrivals', 'reviews', 'page1Cats', 'page2Cats', 'page3Cats', 'page4Cats', 'page5Cats'));
    }
}
