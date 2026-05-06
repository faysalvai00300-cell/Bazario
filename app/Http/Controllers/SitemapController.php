<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::active()->latest()->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return response()->view('sitemap', [
            'products' => $products,
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }
}
