@extends('layouts.admin')
@section('title', 'Show Pop Up')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Website Entry Pop Up</h2>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-8 w-full max-w-3xl dark:bg-gray-800 dark:border-gray-700 transition-colors">
    <form action="{{ route('admin.popup.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <div class="space-y-5">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2 dark:text-white dark:border-gray-700">🖼️ Pop Up Image Banner</h3>
            
            <div class="mt-4">
                <label class="flex items-center gap-3 cursor-pointer mb-5 bg-gray-50 border border-gray-200 p-3 rounded-xl hover:bg-orange-50 transition-colors">
                    <input type="checkbox" name="is_popup_active" value="1" {{ ($settings->is_popup_active ?? false) ? 'checked' : '' }} class="w-5 h-5 text-orange-500 rounded border-gray-300 focus:ring-orange-500 cursor-pointer">
                    <span class="text-sm text-gray-800 font-semibold">Enable Welcome Pop Up on Website</span>
                </label>

                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Upload Banner Image (Portrait/Square Recommended)</label>
                
                @if(!empty($settings->popup_image))
                <div class="mb-4 relative mx-auto border-2 border-dashed border-gray-200 rounded-2xl overflow-hidden bg-gray-50 shadow-sm p-1" style="max-width: 150px; width: 100%;">
                    <img src="{{ asset('storage/' . $settings->popup_image) }}" alt="Pop Up" class="rounded-xl object-contain" style="width: 100%; height: auto; max-height: 200px; display: block; margin: 0 auto;">
                </div>
                @endif
                
                <input type="file" name="popup_image" accept="image/*"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white file:border-0 file:bg-orange-50 file:text-orange-700 file:font-semibold file:px-4 file:py-1 file:rounded-full file:-ml-2 file:mr-4 hover:file:bg-orange-100 cursor-pointer">
                <p class="text-xs text-gray-400 mt-2 dark:text-gray-500 mb-5">Leaving this empty will keep the current image (if any). Only common image formats are allowed.</p>

                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Pop Up Redirect Link (Optional)</label>
                <input type="text" name="popup_link" value="{{ $settings->popup_link ?? '' }}" placeholder="e.g. /category/perfumes or https://google.com"
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                <p class="text-xs text-gray-400 mt-2 dark:text-gray-500">The user will be redirected here when they click the image. You can use a full URL or a page path.</p>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <button type="submit" class="w-full sm:w-auto admin-primary-btn px-8 py-3 rounded-xl text-sm font-semibold">Save Pop Up Settings</button>
        </div>
    </form>
</div>
@endsection
