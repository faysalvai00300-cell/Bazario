<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(static function($q) use ($search) {
                $q->where('reviewer_name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'title'         => 'required|string|max:200',
            'body'          => 'required|string',
            'rating'        => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'reviewer_name' => $request->reviewer_name,
            'title'         => $request->title,
            'body'          => $request->body,
            'rating'        => $request->rating,
            'is_approved'   => true,
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'title'         => 'required|string|max:200',
            'body'          => 'required|string',
            'rating'        => 'required|integer|min:1|max:5',
        ]);

        $review->update([
            'reviewer_name' => $request->reviewer_name,
            'title'         => $request->title,
            'body'          => $request->body,
            'rating'        => $request->rating,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully!');
    }
}
