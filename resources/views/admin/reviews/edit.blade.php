@extends('layouts.admin')
@section('title', 'Edit Review')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reviews.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-orange-500 transition-colors shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Review</h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">কাস্টমার নাম *</label>
                    <input type="text" name="reviewer_name" required value="{{ old('reviewer_name', $review->reviewer_name) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    @error('reviewer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">রেটিং *</label>
                    <select name="rating" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        <option value="5" {{ old('rating', $review->rating) == 5 ? 'selected' : '' }}>★★★★★ (5)</option>
                        <option value="4" {{ old('rating', $review->rating) == 4 ? 'selected' : '' }}>★★★★☆ (4)</option>
                        <option value="3" {{ old('rating', $review->rating) == 3 ? 'selected' : '' }}>★★★☆☆ (3)</option>
                        <option value="2" {{ old('rating', $review->rating) == 2 ? 'selected' : '' }}>★★☆☆☆ (2)</option>
                        <option value="1" {{ old('rating', $review->rating) == 1 ? 'selected' : '' }}>★☆☆☆☆ (1)</option>
                    </select>
                    @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">রিভিউ শিরোনাম *</label>
                    <input type="text" name="title" required value="{{ old('title', $review->title) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">রিভিউ বিবরণ *</label>
                    <textarea name="body" required rows="5" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">{{ old('body', $review->body) }}</textarea>
                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary px-8 py-3 rounded-xl font-bold shadow-lg shadow-orange-200 transition-all hover:scale-105 active:scale-95">
                    Update Review
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
