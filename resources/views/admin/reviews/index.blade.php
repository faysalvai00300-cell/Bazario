@extends('layouts.admin')
@section('title', 'Manage Reviews')
@section('content')
<div class="space-y-6">

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    <!-- Add Review Form -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-lg font-bold text-gray-900 mb-5 dark:text-white">নতুন রিভিউ যোগ করুন</h2>
        <form action="{{ route('admin.reviews.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">কাস্টমার নাম *</label>
                    <input type="text" name="reviewer_name" required placeholder="e.g. Ahmed Rahman" value="{{ old('reviewer_name') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    @error('reviewer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">রেটিং *</label>
                    <select name="rating" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        <option value="">-- বেছে নিন --</option>
                        <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>★★★★★ (5)</option>
                        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>★★★★☆ (4)</option>
                        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>★★★☆☆ (3)</option>
                        <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>★★☆☆☆ (2)</option>
                        <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>★☆☆☆☆ (1)</option>
                    </select>
                    @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">রিভিউ শিরোনাম *</label>
                    <input type="text" name="title" required placeholder="e.g. Excellent Product!" value="{{ old('title') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">রিভিউ বিবরণ *</label>
                    <textarea name="body" required rows="3" placeholder="রিভিউ লিখুন..." class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">{{ old('body') }}</textarea>
                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <button type="submit" class="btn-primary font-semibold px-6 py-2.5 rounded-xl text-sm transition shadow-sm">
                + রিভিউ যোগ করুন
            </button>
        </form>
    </div>

    <!-- Review List -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 dark:border-gray-700">
            <h2 class="font-bold text-gray-900 dark:text-white whitespace-nowrap">সকল রিভিউ ({{ $reviews->count() }})</h2>
            <form action="{{ route('admin.reviews.index') }}" method="GET" class="relative group w-full max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, title or body..." 
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50 transition-all">
            </form>
        </div>
        @if($reviews->isEmpty())
        <p class="text-gray-500 text-sm text-center py-10 dark:text-gray-400">কোনো রিভিউ নেই।</p>
        @else
        <div class="divide-y divide-gray-50 dark:divide-gray-700">
            @foreach($reviews as $review)
            <div class="px-6 py-4 flex items-start justify-between gap-4 group">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $review->reviewer_name ?? 'Anonymous' }}</span>
                        <span class="text-yellow-400 text-xs">
                            @for($s = 1; $s <= 5; $s++){{ $s <= $review->rating ? '★' : '☆' }}@endfor
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $review->title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 dark:text-gray-400">{{ $review->body }}</p>
                    <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.reviews.edit', $review) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-900/30">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </a>
                    <a href="{{ route('admin.reviews.delete', $review) }}" 
                       onclick="return confirm('নিশ্চিত তো? এই রিভিউটি ডিলিট হয়ে যাবে।')" 
                       class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30 dark:hover:bg-red-600 dark:hover:text-white">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
