@extends('layouts.app')
@section('title', 'About Us - Bazario')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-4">About <span class="text-[#FF6A00]">Bazario</span></h1>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto">Your trusted online shopping destination for premium products at unbeatable prices.</p>
    </div>
    
    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100 mb-12">
        <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed mb-6">At Bazario, our mission is simple: to provide high-quality, premium products to customers all over Bangladesh with the most seamless and trustworthy shopping experience possible. We believe that shopping online should be fast, secure, and enjoyable.</p>
                <p class="text-gray-600 leading-relaxed">Whether you are looking for the latest electronics, trendy fashion accessories, or everyday essentials, we carefully curate our inventory to ensure you get the best value for your money.</p>
            </div>
            <div class="bg-orange-50 rounded-2xl p-8 border border-orange-100 text-center">
                <div class="w-16 h-16 bg-[#FF6A00] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Fast & Secure</h3>
                <p class="text-sm text-gray-600">We prioritize lightning-fast delivery and top-notch security for all your transactions.</p>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Why Choose Us?</h2>
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="text-center p-6 border border-gray-100 rounded-2xl bg-gray-50">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-2xl">🏆</div>
                    <h4 class="font-bold text-gray-900 mb-2">Premium Quality</h4>
                    <p class="text-xs text-gray-500">Every product is verified for quality before it reaches your door.</p>
                </div>
                <div class="text-center p-6 border border-gray-100 rounded-2xl bg-gray-50">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-2xl">🛡️</div>
                    <h4 class="font-bold text-gray-900 mb-2">Secure Shopping</h4>
                    <p class="text-xs text-gray-500">Your data and payments are protected with industry-standard encryption.</p>
                </div>
                <div class="text-center p-6 border border-gray-100 rounded-2xl bg-gray-50">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-2xl">🎧</div>
                    <h4 class="font-bold text-gray-900 mb-2">24/7 Support</h4>
                    <p class="text-xs text-gray-500">Our customer service team is always ready to assist you.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Ready to start shopping?</h3>
        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-[#FF6A00] border border-transparent rounded-full hover:bg-[#FF7A1A] hover:shadow-lg hover:shadow-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600">
            Explore Our Products
        </a>
    </div>
</div>
@endsection
