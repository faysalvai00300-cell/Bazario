<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MiddleBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('type', 'promo')->orderBy('sort_order')->get();
        return view('admin.middle-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.middle-banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
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

        $data['type'] = 'promo';
        $data['is_active'] = $request->boolean('is_active', true);
        $data['show_on_mobile'] = $request->boolean('show_on_mobile', true);
        $data['show_on_desktop'] = $request->boolean('show_on_desktop', true);
        $data['sort_order'] = Banner::where('type', 'promo')->max('sort_order') + 1;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        Banner::create($data);

        return redirect()->route('admin.middle-banners.index')->with('success', 'Middle Banner created!');
    }

    public function edit(Banner $middleBanner)
    {
        // Ensure it's a promo banner
        if ($middleBanner->type !== 'promo') {
            abort(404);
        }
        $banner = $middleBanner;
        return view('admin.middle-banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $middleBanner)
    {
        if ($middleBanner->type !== 'promo') {
            abort(404);
        }

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
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
            if ($middleBanner->image && !Str::startsWith($middleBanner->image, 'http')) {
                Storage::disk('public')->delete($middleBanner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        } elseif ($request->filled('image_url')) {
            if ($middleBanner->image && !Str::startsWith($middleBanner->image, 'http')) {
                Storage::disk('public')->delete($middleBanner->image);
            }
            $data['image'] = $request->image_url;
        }

        $middleBanner->update($data);

        return redirect()->route('admin.middle-banners.index')->with('success', 'Middle Banner updated!');
    }

    public function destroy(Banner $middleBanner)
    {
        if ($middleBanner->type !== 'promo') {
            abort(404);
        }

        if ($middleBanner->image && !Str::startsWith($middleBanner->image, 'http')) {
            Storage::disk('public')->delete($middleBanner->image);
        }
        $middleBanner->delete();
        return redirect()->route('admin.middle-banners.index')->with('success', 'Middle Banner deleted!');
    }
}
