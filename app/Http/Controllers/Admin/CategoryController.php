<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('target_page')) {
            $query->where('target_page', $request->target_page);
        }

        $categories = $query->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $occupiedBoxes = Category::whereNotNull('target_page')
            ->whereNotNull('target_box')
            ->get()
            ->groupBy('target_page')
            ->map(function ($items) {
                return $items->pluck('name', 'target_box');
            });

        $categories = Category::all();
        return view('admin.categories.create', compact('occupiedBoxes', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'sort_order'  => 'required|integer',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:10240',
            'view_all_image' => 'nullable|image|max:10240',
            'linked_category_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'target_page' => 'nullable|integer|between:1,5',
            'target_box' => 'nullable|integer',
            'target_gender' => 'nullable|array',
            'target_gender.*' => 'in:Men,Women,Kids,Sports',
        ]);

        $data['slug']      = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active');
        $data['icon']      = $request->icon ?? '📁';
        $data['color']     = $request->color ?? '#3B82F6';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->hasFile('view_all_image')) {
            $data['view_all_image'] = $request->file('view_all_image')->store('categories', 'public');
        }

        Category::create($data);
        return redirect()->route('admin.categories.index', $request->target_page ? ['target_page' => $request->target_page] : [])->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
        $occupiedBoxes = Category::whereNotNull('target_page')
            ->whereNotNull('target_box')
            ->get()
            ->groupBy('target_page')
            ->map(function ($items) {
                return $items->pluck('name', 'target_box');
            });

        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'occupiedBoxes', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'sort_order'  => 'required|integer',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:10240',
            'view_all_image' => 'nullable|image|max:10240',
            'linked_category_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'target_page' => 'nullable|integer|between:1,5',
            'target_box' => 'nullable|integer',
            'target_gender' => 'nullable|array',
            'target_gender.*' => 'in:Men,Women,Kids,Sports',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && \Storage::disk('public')->exists($category->image)) {
                \Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->hasFile('view_all_image')) {
            // Delete old image if exists
            if ($category->view_all_image && \Storage::disk('public')->exists($category->view_all_image)) {
                \Storage::disk('public')->delete($category->view_all_image);
            }
            $data['view_all_image'] = $request->file('view_all_image')->store('categories', 'public');
        }

        $category->update($data);
        return redirect()->route('admin.categories.index', $request->target_page ? ['target_page' => $request->target_page] : [])->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products.');
        }
        if ($category->image && \Storage::disk('public')->exists($category->image)) {
            \Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted!');
    }
}
