@extends('layouts.app')
@section('title', 'Categories - SmartLookBD')
@section('body_bg', '#f2f5f8')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-4 md:py-8">
    <div class="grid grid-cols-2 xs:grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-6">
        @foreach($categories as $category)
        <a href="{{ route('category.show', $category->slug) }}" class="bg-white rounded-sm p-4 flex flex-col items-center shadow-sm border border-gray-50 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
            <!-- Circular Image Wrapper with Orange Border -->
            <div class="relative w-20 h-20 xs:w-24 xs:h-24 rounded-full border-2 border-[#FF6A00] p-1 flex items-center justify-center bg-white overflow-hidden mb-3">
                <div class="w-full h-full rounded-full overflow-hidden bg-gray-50 flex items-center justify-center">
                    @if($category->image)
                        <img src="{{ Str::startsWith($category->image, ['http://', 'https://']) ? $category->image : asset('storage/' . $category->image) }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="hidden w-full h-full items-center justify-center text-3xl bg-gray-50 text-gray-300">
                            {{ $category->icon ?: '📦' }}
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl bg-gray-50 text-gray-300">
                            {{ $category->icon ?: '📦' }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Category Info -->
            <h3 class="text-xs md:text-sm font-black text-[#001b3a] text-center line-clamp-1 group-hover:text-[#FF6A00] transition-colors uppercase tracking-tight px-1">{{ $category->name }}</h3>
            
            <p class="text-[10px] md:text-xs text-gray-400 mt-1 font-medium whitespace-nowrap">
                {{ $category->products_count }} {{ $category->products_count <= 1 ? 'item' : 'items' }}
            </p>
        </a>
        @endforeach
    </div>

    @if($categories->isEmpty())
    <div class="text-center py-20">
        <p class="text-gray-400 font-medium font-inter uppercase text-xs tracking-widest">No categories found</p>
    </div>
    @endif
</div>

<style>
    /* Ensure the grid fits well on very small screens */
    @media (max-width: 480px) {
        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endsection