<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('type', 'hero')->orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'show_on_mobile' => 'boolean',
            'show_on_desktop' => 'boolean',
        ]);

        $data['type'] = 'hero';
        $data['is_active'] = $request->boolean('is_active', true);
        $data['show_on_mobile'] = $request->boolean('show_on_mobile', true);
        $data['show_on_desktop'] = $request->boolean('show_on_desktop', true);
        $data['sort_order'] = Banner::where('type', 'hero')->max('sort_order') + 1; // Default to end

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }
        elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }
        else {
            $data['image'] = null;
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'show_on_mobile' => 'boolean',
            'show_on_desktop' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['show_on_mobile'] = $request->boolean('show_on_mobile');
        $data['show_on_desktop'] = $request->boolean('show_on_desktop');

        if ($request->hasFile('image')) {
            if ($banner->image && !Str::startsWith($banner->image, 'http')) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }
        elseif ($request->filled('image_url')) {
            if ($banner->image && !Str::startsWith($banner->image, 'http')) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->image_url;
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated!');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && !Str::startsWith($banner->image, 'http')) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted!');
    }
}
