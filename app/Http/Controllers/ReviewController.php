<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body' => 'required|string|min:10',
            'title' => 'nullable|string|max:100',
        ]);

        $existing = Review::where('user_id', auth()->id())->where('product_id', $productId)->first();
        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body,
            'rating' => $request->rating,
            'reviewer_name' => auth()->user()->name,
            'is_approved' => true,
        ]);

        $product = Product::findOrFail($productId);
        $avg = Review::where('product_id', $productId)->where('is_approved', true)->avg('rating');
        $count = Review::where('product_id', $productId)->where('is_approved', true)->count();
        $product->update(['rating' => round($avg, 1), 'review_count' => $count]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
