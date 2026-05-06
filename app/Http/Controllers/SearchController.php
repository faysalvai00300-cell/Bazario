<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function live(Request $request)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) return response()->json([]);

        $products = Product::active()
            ->where(function($q) use ($query) {
                $cleanQuery = str_replace(' ', '', $query);
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('sku', $query)
                  ->orWhere('sku', $cleanQuery)
                  ->orWhere('brand', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'slug', 'price', 'sale_price', 'thumbnail')
            ->take(8)
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'effective_price' => $p->effective_price,
                    'thumbnail_url' => $p->thumbnail_url,
                ];
            });

        return response()->json($products);
    }
}
