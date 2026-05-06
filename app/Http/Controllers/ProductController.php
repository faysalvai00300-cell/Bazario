<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        if ($request->filled('q')) {
            $searchTerm = trim($request->q);
            $cleanSearchTerm = str_replace(' ', '', $searchTerm);
            $query->where(function($subQuery) use ($searchTerm, $cleanSearchTerm) {
                $subQuery->where('name', 'like', "%{$searchTerm}%")
                         ->orWhere('description', 'like', "%{$searchTerm}%")
                         ->orWhere('sku', 'like', "%{$searchTerm}%")
                         ->orWhere('sku', $searchTerm)
                         ->orWhere('sku', $cleanSearchTerm)
                         ->orWhere('brand', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('gender')) {
            $genderVal = $request->gender;
            $query->whereHas('category', function($q) use ($genderVal) {
                $q->whereJsonContains('target_gender', $genderVal);
            });
        }

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = Category::where('slug', $request->category)->first();
            if ($activeCategory) {
                $categoryIds = [$activeCategory->id];
                if ($activeCategory->linked_category_id) {
                    $categoryIds[] = $activeCategory->linked_category_id;
                }
                $query->whereIn('category_id', $categoryIds);
                $activeCategory->load(['subcategories' => function($q) {
                    $q->where('is_active', true)->withCount('products')->orderBy('sort_order');
                }]);
            }
        }

        if ($request->filled('subcategory')) {
            $subcat = Subcategory::where('slug', $request->subcategory)->first();
            if ($subcat) {
                $query->where('subcategory_id', $subcat->id);
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('new')) {
            $query->where('is_new', true);
        }

        if ($request->boolean('mega_deal')) {
            $query->where('is_mega_deal', true);
        }

        if ($request->boolean('free_shipping')) {
            $query->where('free_shipping', true);
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->latest();
        }

        $products = $query->get();
        $categories = Category::where('is_active', true)->withCount(['products' => function($q) { $q->where('is_active', true); }])->orderBy('sort_order')->get();
        
        // Adjust counts for linked categories
        foreach ($categories as $cat) {
            if ($cat->linked_category_id) {
                // Get the linked category's own product count
                $linkedCatCount = Product::active()->where('category_id', $cat->linked_category_id)->count();
                $cat->products_count += $linkedCatCount;
            }
        }
        
        $maxPrice = 30000;
        $minPrice = 0;
        
        if ($request->filled('q')) {
            $pageTitle = 'Search: "' . $request->q . '"';
        } elseif ($request->filled('gender')) {
            $pageTitle = $request->gender . "'s Collection";
        } elseif ($request->boolean('mega_deal')) {
            $pageTitle = 'Mega Deals';
        } elseif ($request->boolean('new')) {
            $pageTitle = 'New Arrivals';
        } else {
            $pageTitle = 'All Products';
        }

        $suggestions = [];
        if ($request->filled('category')) {
            $activeCat = Category::where('slug', $request->category)->first();
            if ($activeCat) {
                $otherCats = Category::where('is_active', true)
                    ->where('id', '!=', $activeCat->id)
                    ->orderBy('sort_order')
                    ->get();
                
                foreach ($otherCats as $oc) {
                    $ocProducts = Product::active()->where('category_id', $oc->id)->latest()->take(2)->get();
                    if ($ocProducts->count() > 0) {
                        $suggestions[] = ['category' => $oc, 'products' => $ocProducts];
                    }
                }
            }
        }

        return view('products.index', compact('products', 'categories', 'pageTitle', 'suggestions', 'activeCategory', 'minPrice', 'maxPrice'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'reviews.user', 'flashSales']);
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        if ($relatedProducts->count() < 10) {
            $fillerCount = 10 - $relatedProducts->count();
            $fillerProducts = Product::active()
                ->where('category_id', '!=', $product->category_id)
                ->inRandomOrder()
                ->take($fillerCount)
                ->get();
            $relatedProducts = $relatedProducts->concat($fillerProducts);
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }
    public function categories()
    {
        $categories = Category::where('is_active', true)->withCount(['products' => function($q) { $q->where('is_active', true); }])->orderBy('sort_order')->get();
        
        // Adjust counts for linked categories
        foreach ($categories as $cat) {
            if ($cat->linked_category_id) {
                $linkedCatCount = Product::active()->where('category_id', $cat->linked_category_id)->count();
                $cat->products_count += $linkedCatCount;
            }
        }

        return view('products.categories', compact('categories'));
    }

    public function category(\App\Models\Category $category, Request $request)
    {
        $categoryIds = [$category->id];
        if ($category->linked_category_id) {
            $categoryIds[] = $category->linked_category_id;
        }

        $query = Product::active()
            ->whereIn('category_id', $categoryIds)
            ->with('category');

        if ($request->filled('subcategory')) {
            $subcat = Subcategory::where('slug', $request->subcategory)->first();
            if ($subcat) {
                $query->where('subcategory_id', $subcat->id);
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->get();
        $maxPrice = 30000;
        $minPrice = 0;
        $categories = Category::where('is_active', true)->withCount(['products' => function($q) { $q->where('is_active', true); }])->orderBy('sort_order')->get();
        
        // Adjust counts for linked categories
        foreach ($categories as $cat) {
            if ($cat->linked_category_id) {
                $linkedCatCount = Product::active()->where('category_id', $cat->linked_category_id)->count();
                $cat->products_count += $linkedCatCount;
            }
        }
        $pageTitle = $category->name;

        // Load subcategories for the active category
        $activeCategory = $category;
        $activeCategory->load(['subcategories' => function($q) {
            $q->where('is_active', true)->withCount('products')->orderBy('sort_order');
        }]);

        $suggestions = [];
        $otherCats = Category::where('is_active', true)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->get();
        
        foreach ($otherCats as $oc) {
            $ocProducts = Product::active()->where('category_id', $oc->id)->latest()->take(2)->get();
            if ($ocProducts->count() > 0) {
                $suggestions[] = ['category' => $oc, 'products' => $ocProducts];
            }
        }

        return view('products.index', compact('products', 'categories', 'pageTitle', 'suggestions', 'activeCategory', 'minPrice', 'maxPrice'));
    }

    public function flashSales()
    {
        $products = Product::active()
            ->whereHas('flashSales', function($query) {
                $query->where('is_active', true)
                      ->where('ends_at', '>', now());
            })
            ->with(['flashSales' => function($query) {
                $query->where('is_active', true)->where('ends_at', '>', now());
            }])
            ->latest()
            ->get();

        $maxPrice = 30000;
        $minPrice = 0;

        $categories = Category::where('is_active', true)->withCount(['products' => function($q) { $q->where('is_active', true); }])->orderBy('sort_order')->get();
        
        // Adjust counts for linked categories
        foreach ($categories as $cat) {
            if ($cat->linked_category_id) {
                $linkedCatCount = Product::active()->where('category_id', $cat->linked_category_id)->count();
                $cat->products_count += $linkedCatCount;
            }
        }
        $pageTitle = '🔥 Flash Sale';

        return view('products.index', compact('products', 'categories', 'pageTitle', 'minPrice', 'maxPrice'));
    }

    public function getDetails($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'thumbnail_url' => $product->thumbnail_url,
            'price' => $product->price,
            'effective_price' => $product->effective_price,
            'sizes' => $product->sizes ?? [],
            'colors' => $product->colors ?? [],
            'free_shipping' => $product->free_shipping,
        ]);
    }
}
