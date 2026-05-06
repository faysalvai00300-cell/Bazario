@extends('layouts.admin')

@section('title', 'Product Image Slider Settings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Product Image Slider</h1>
        <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">Configure how product images slide automatically on product cards.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <form action="{{ route('admin.settings.slider.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2 dark:text-white dark:border-gray-700">🖼️ Image Slider Configuration</h3>
            
            <div>
                <label class="flex items-center gap-3 cursor-pointer mb-5 bg-gray-50 border border-gray-200 p-3 rounded-xl hover:bg-orange-50 transition-colors">
                    <input type="checkbox" name="is_product_slider_active" value="1" {{ ($settings->is_product_slider_active ?? true) ? 'checked' : '' }} class="w-5 h-5 text-orange-500 rounded border-gray-300 focus:ring-orange-500 cursor-pointer">
                    <span class="text-sm text-gray-800 font-semibold">Enable Product Image Auto Slider</span>
                </label>

                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Slider Interval Speed</label>
                <select name="product_slider_interval" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="3000" {{ (($settings->product_slider_interval ?? 4000) == 3000) ? 'selected' : '' }}>Rapid (3s)</option>
                    <option value="4000" {{ (($settings->product_slider_interval ?? 4000) == 4000) ? 'selected' : '' }}>Fast (4s)</option>
                    <option value="6000" {{ (($settings->product_slider_interval ?? 4000) == 6000) ? 'selected' : '' }}>Normal (6s)</option>
                    <option value="8000" {{ (($settings->product_slider_interval ?? 4000) == 8000) ? 'selected' : '' }}>Relaxed (8s)</option>
                </select>
            </div>
        </div>

        <div class="pt-8 mt-6 border-t border-gray-100 flex justify-end dark:border-gray-700">
            <button type="submit" class="admin-primary-btn px-8 py-2.5 rounded-xl text-sm font-semibold">Save Slider Settings</button>
        </div>
    </form>
</div>
@endsection
