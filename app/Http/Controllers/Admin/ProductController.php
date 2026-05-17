<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(static function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'instock') {
                $query->where('stock', '>', 10);
            } elseif ($request->stock_status == 'outofstock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status == 'lowstock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
            }
        }

        if ($request->filled('type')) {
            if ($request->type == 'new') {
                $query->where('is_new', true);
            } elseif ($request->type == 'featured') {
                $query->where('is_featured', true);
            } elseif ($request->type == 'top_sell') {
                $query->where('is_top_sell', true);
            }
        }

        if ($request->filled('sort')) {
            if ($request->sort == 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_high') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort == 'stock_low') {
                $query->orderBy('stock', 'asc');
            } elseif ($request->sort == 'stock_high') {
                $query->orderBy('stock', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(20)->withQueryString();
        
        $categoriesQuery = Category::where('is_active', true);
        if ($request->filled('target_page')) {
            $categoriesQuery->where('target_page', $request->target_page);
        }
        $categories = $categoriesQuery->get();

        // Store the current URL in session to redirect back after edits/deletes
        session(['last_products_url' => $request->fullUrl()]);

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        $featuredCounts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->get()
            ->groupBy('category_id')
            ->map(function($group) {
                return $group->count();
            });

        return view('admin.products.create', compact('categories', 'featuredCounts'));
    }

    public function store(Request $request)
    {
        if ($request->filled('video_url')) {
            $videoUrl = $request->video_url;
            if (str_contains($videoUrl, '<iframe')) {
                if (preg_match('/src="([^"]+)"/', $videoUrl, $match)) {
                    $request->merge(['video_url' => $match[1]]);
                }
            }
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'buying_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'brand' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_top_sell' => 'boolean',
            'is_active' => 'boolean',
            'sizes' => 'nullable|string',
            'colors' => 'nullable',
            'free_shipping' => 'boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'thumbnail_url' => 'nullable|url|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'video_url' => 'nullable|string|max:1000',
            'size_chart' => 'nullable|string',
            'is_mega_deal' => 'boolean',
            'mega_deal_effect' => 'nullable|string|in:none,shine,border,confetti,shine_border,all',
            'color_swatches.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'color_image_indices.*' => 'nullable|integer|min:0',
        ]);

        if (isset($data['sizes'])) {
            $data['sizes'] = array_map('trim', explode(',', $data['sizes']));
        }
        if (isset($data['colors'])) {
            if (is_array($data['colors'])) {
                $data['colors'] = array_map('trim', $data['colors']);
            } else {
                $data['colors'] = array_map('trim', explode(',', $data['colors']));
            }
        }

        $data['slug'] = Str::limit(Str::slug($request->name), 200, '') . '-' . Str::random(5);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_mega_deal'] = $request->boolean('is_mega_deal');
        $data['mega_deal_effect'] = $request->input('mega_deal_effect', 'all');
        $data['is_new'] = $request->boolean('is_new', true); // Default new products to new
        $data['is_top_sell'] = $request->boolean('is_top_sell');
        $data['is_active'] = $request->boolean('is_active', true); // Default to active
        $data['is_size_chart_active'] = $request->boolean('is_size_chart_active', true);
        $data['free_shipping'] = $request->boolean('free_shipping');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
        elseif ($request->filled('thumbnail_url')) {
            $data['thumbnail'] = $request->thumbnail_url;
        }
        else {
            $data['thumbnail'] = null;
        }

        if (empty($data['meta_title'])) {
            $data['meta_title'] = Str::limit("Buy {$data['name']} Online at Best Price in BD - " . config('app.name'), 255);
        }
        
        $category = Category::find($data['category_id']);
        $catName = $category ? $category->name : '';
        
        if (empty($data['meta_description'])) {
            $descText = !empty($data['description']) ? Str::limit(strip_tags($data['description']), 100) : "Buy " . Str::limit($data['name'], 50) . " at the lowest price.";
            $data['meta_description'] = Str::limit("Looking for {$data['name']}? {$descText} Top quality {$catName} products with fast delivery in Bangladesh.", 255);
        }
        
        if (empty($data['meta_keywords'])) {
            $brand = !empty($data['brand']) ? ', ' . $data['brand'] : '';
            $catKeyword = $catName ? ", {$catName}, best in bd" : '';
            $data['meta_keywords'] = Str::limit("{$data['name']}, buy {$data['name']}{$catKeyword}{$brand}, " . strtolower(config('app.name')), 255);
        }
        if ($request->filled('size_chart')) {
            $data['size_chart'] = json_decode($request->size_chart, true);
        }

        // Handle Color Swatches
        $swatches = [];
        if ($request->hasFile('color_swatches')) {
            foreach ($request->file('color_swatches') as $file) {
                if ($file) {
                    $swatches[] = $file->store('products/swatches', 'public');
                } else {
                    $swatches[] = null;
                }
            }
        }
        $data['color_swatches'] = $swatches;
        $data['color_image_indices'] = $request->input('color_image_indices', []);

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect(session('last_products_url', route('admin.products.index')))->with('success', 'Product created!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $featuredCounts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->get()
            ->groupBy('category_id')
            ->map(function($group) {
                return $group->count();
            });

        return view('admin.products.edit', compact('product', 'categories', 'featuredCounts'));
    }

    public function update(Request $request, Product $product)
    {
        if ($request->filled('video_url')) {
            $videoUrl = $request->video_url;
            if (str_contains($videoUrl, '<iframe')) {
                if (preg_match('/src="([^"]+)"/', $videoUrl, $match)) {
                    $request->merge(['video_url' => $match[1]]);
                }
            }
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'buying_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'brand' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_top_sell' => 'boolean',
            'is_active' => 'boolean',
            'sizes' => 'nullable|string',
            'colors' => 'nullable',
            'free_shipping' => 'boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'thumbnail_url' => 'nullable|url|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'video_url' => 'nullable|string|max:1000',
            'size_chart' => 'nullable|string',
            'is_mega_deal' => 'boolean',
            'mega_deal_effect' => 'nullable|string|in:none,shine,border,confetti,shine_border,all',
            'color_swatches.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'existing_swatches.*' => 'nullable|string',
            'color_image_indices.*' => 'nullable|integer|min:0',
        ]);

        // Ensure sizes and colors are handled even if they are empty/missing from request
        $data['sizes'] = $request->filled('sizes') 
            ? array_map('trim', explode(',', $request->sizes)) 
            : null;
            
        $data['colors'] = $request->has('colors') 
            ? (is_array($request->colors) ? array_map('trim', $request->colors) : array_map('trim', explode(',', $request->colors)))
            : null;

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_mega_deal'] = $request->boolean('is_mega_deal');
        $data['mega_deal_effect'] = $request->input('mega_deal_effect', 'all');
        $data['is_new'] = $request->boolean('is_new');
        $data['is_top_sell'] = $request->boolean('is_top_sell');
        $data['is_active'] = $request->boolean('is_active');
        $data['is_size_chart_active'] = $request->boolean('is_size_chart_active');
        $data['free_shipping'] = $request->boolean('free_shipping');

        if ($request->hasFile('thumbnail')) {
            // Delete old image if exists and is not a remote URL
            if ($product->thumbnail && !Str::startsWith($product->thumbnail, 'http')) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
        elseif ($request->filled('thumbnail_url')) {
            if ($product->thumbnail && !Str::startsWith($product->thumbnail, 'http')) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->thumbnail_url;
        }

        if (empty($data['meta_title'])) {
            $data['meta_title'] = Str::limit("Buy {$data['name']} Online at Best Price in BD - " . config('app.name'), 255);
        }
        
        $category = Category::find($data['category_id']);
        $catName = $category ? $category->name : '';
        
        if (empty($data['meta_description'])) {
            $descText = !empty($data['description']) ? Str::limit(strip_tags($data['description']), 100) : "Buy " . Str::limit($data['name'], 50) . " at the lowest price.";
            $data['meta_description'] = Str::limit("Looking for {$data['name']}? {$descText} Top quality {$catName} products with fast delivery in Bangladesh.", 255);
        }
        
        if (empty($data['meta_keywords'])) {
            $brand = !empty($data['brand']) ? ', ' . $data['brand'] : '';
            $catKeyword = $catName ? ", {$catName}, best in bd" : '';
            $data['meta_keywords'] = Str::limit("{$data['name']}, buy {$data['name']}{$catKeyword}{$brand}, " . strtolower(config('app.name')), 255);
        }
        if ($request->filled('size_chart')) {
            $data['size_chart'] = json_decode($request->size_chart, true);
        }

        // Handle Color Swatches in Update
        $finalSwatches = [];
        $existingSwatches = $request->input('existing_swatches', []);
        $newSwatches = $request->file('color_swatches', []);
        
        // Colors array is already set in $data['colors']
        if (isset($data['colors']) && is_array($data['colors'])) {
            foreach ($data['colors'] as $index => $colorName) {
                if (isset($newSwatches[$index]) && $newSwatches[$index]) {
                    // Upload new swatch
                    $finalSwatches[] = $newSwatches[$index]->store('products/swatches', 'public');
                } elseif (isset($existingSwatches[$index])) {
                    // Keep existing swatch
                    $finalSwatches[] = $existingSwatches[$index];
                } else {
                    $finalSwatches[] = null;
                }
            }
        }
        $data['color_swatches'] = $finalSwatches;
        $data['color_image_indices'] = $request->input('color_image_indices', []);

        $product->update($data);

        // Handle reordering of existing images
        if ($request->filled('image_order')) {
            $orderIds = explode(',', $request->image_order);
            foreach ($orderIds as $index => $id) {
                \App\Models\ProductImage::where('id', $id)
                    ->where('product_id', $product->id)
                    ->update(['sort_order' => $index]);
            }
        }

        if ($request->hasFile('images')) {
            $maxOrder = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return redirect(session('last_products_url', route('admin.products.index')))->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail && !Str::startsWith($product->thumbnail, 'http')) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }
        $product->delete();
        return redirect(session('last_products_url', route('admin.products.index')))->with('success', 'Product deleted!');
    }

    public function toggleStatus($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $field = $request->field;
        if (!in_array($field, ['is_featured', 'is_new', 'is_top_sell', 'is_active'])) {
            return response()->json(['success' => false, 'message' => 'Invalid field']);
        }

        $product->$field = !$product->$field;
        $product->save();

        return response()->json(['success' => true, 'value' => $product->$field]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'No products selected');
        }

        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            if ($product->thumbnail && !Str::startsWith($product->thumbnail, 'http')) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image);
                $image->delete();
            }
            $product->delete();
        }

        return back()->with('success', 'Selected products deleted!');
    }

    public function destroyImage(ProductImage $productImage)
    {
        if ($productImage->image && !Str::startsWith($productImage->image, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($productImage->image);
        }
        $productImage->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Image removed from gallery.');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.show', $product->slug);
    }
}
