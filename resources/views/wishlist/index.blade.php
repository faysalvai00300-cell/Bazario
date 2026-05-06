@extends('layouts.app')

@section('title', 'My Wishlist - SmartLookBD')
@section('body_bg', '#FFFFFF')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 md:px-12 py-12 md:py-20 min-h-[70vh]">
    
    {{-- Header Section --}}
    <div class="text-center mb-12 md:mb-20" data-aos="fade-down">
        <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tighter uppercase mb-2">My Wishlist</h1>
        <div class="h-1.5 w-24 bg-[#45b86f] mx-auto rounded-full mb-6 shadow-sm"></div>
        <p class="text-gray-500 text-sm md:text-base font-medium max-w-lg mx-auto leading-relaxed">
            @guest 
                Keep track of the products you love. <a href="{{ route('login') }}" class="text-[#45b86f] font-bold hover:underline">Log in</a> to save them to your account permanently.
            @else
                Welcome back, {{ explode(' ', auth()->user()->name)[0] }}. Here are your saved favorites.
            @endguest
        </p>
    </div>

    @if($wishlistItems->count() > 0)
        {{-- Wishlist Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-8">
            @foreach($wishlistItems as $item)
                <div class="relative group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    @include('partials.product-card', ['product' => $item->product])
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty Wishlist --}}
        <div class="flex flex-col items-center justify-center py-20 text-center" data-aos="zoom-in">
            <div class="w-32 h-32 bg-[#F8F9FA] rounded-full flex items-center justify-center mb-10 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.05)] border border-white group transition-all duration-700 hover:bg-[#45b86f]/5">
                <svg class="w-16 h-16 text-gray-200 group-hover:text-[#45b86f] transition-all duration-500 transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4 uppercase tracking-tighter">Your wishlist is empty</h2>
            <p class="text-gray-500 mb-12 max-w-md text-sm md:text-base leading-relaxed px-6">Explore our latest arrivals and find the scents that speak to you. Save them here for later!</p>
            @guest
                <a href="{{ route('login') }}" class="w-full max-w-[320px] mx-auto text-white px-10 py-5 rounded-2xl text-[15px] font-black uppercase tracking-widest hover:brightness-110 transition-all duration-300 text-center shadow-[0_20px_50px_-10px_rgba(0,0,0,0.3)] active:scale-95 flex items-center justify-center gap-3" style="background-color: #000000 !important; color: #ffffff !important;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Continue to Login
                </a>
            @else
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-4 px-12 py-5 rounded-full text-[14px] font-black uppercase tracking-[0.2em] hover:bg-[#45b86f] transition-all duration-500 shadow-2xl shadow-gray-200 active:scale-95 group" style="background-color: #000000 !important; color: #ffffff !important;">
                    Discover Products
                    <svg class="w-5 h-5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            @endguest
        </div>
    @endif

</div>
@endsection
