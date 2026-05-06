<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index()
    {
        if (!auth()->check()) {
            $wishlistItems = collect();
        } else {
            $wishlistItems = Wishlist::where('user_id', auth()->id())->with('product.category')->latest()->get();
        }
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle($productId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'requires_login' => true
            ]);
        }

        $existing = Wishlist::where('user_id', auth()->id())->where('product_id', $productId)->first();
        if ($existing) {
            $existing->delete();
            return response()->json(['in_wishlist' => false, 'message' => 'Removed from wishlist']);
        }
        Wishlist::create(['user_id' => auth()->id(), 'product_id' => $productId]);
        return response()->json(['in_wishlist' => true, 'message' => 'Added to wishlist!']);
    }
}
