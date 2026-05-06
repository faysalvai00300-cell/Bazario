@extends('layouts.admin')
@section('title', 'Middle Banners Management')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Middle Banners (Promo)</h2>
    <a href="{{ route('admin.middle-banners.create') }}" class="admin-primary-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4 mr-1 inline-block"></i> Add Middle Banner
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach($banners as $banner)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col group p-4 gap-4 dark:bg-gray-800 dark:border-gray-700">
        
        <!-- Banner Image Preview  -->
        <div class="relative w-full h-40 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-900 border-2 border-orange-100 dark:border-orange-900/30 group-hover:border-orange-500/50 transition-all duration-300 ring-4 ring-orange-50/50 dark:ring-orange-900/10">
            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40 flex flex-col justify-center p-6 text-white">
                @if($banner->badge_text)
                <span class="bg-[#FF6A00] text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-widest mb-1 inline-block w-fit">{{ $banner->badge_text }}</span>
                @endif
                <h3 class="text-xl font-black mb-1 leading-tight">{!! $banner->title !!}</h3>
            </div>

            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('admin.middle-banners.edit', $banner) }}" class="p-2 bg-white text-blue-600 rounded-lg shadow-md hover:bg-blue-50 dark:bg-gray-900 dark:text-blue-400 dark:hover:bg-gray-800">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                </a>
                <form action="{{ route('admin.middle-banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Delete this banner?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 bg-white text-red-600 rounded-lg shadow-md hover:bg-red-50 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-gray-800">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5 font-medium {{ $banner->is_active ? 'text-green-600' : 'text-red-500' }} dark:{{ $banner->is_active ? 'text-green-400' : 'text-red-400' }}">
                    <span class="w-2 h-2 rounded-full {{ $banner->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    {{ $banner->is_active ? 'Active' : 'Hidden' }}
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($banners->isEmpty())
    <div class="col-span-full bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
        No middle banners added yet.
    </div>
    @endif
</div>

@endsection
