@extends('layouts.app')
@section('title', 'Contact Us - Bazario')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-black text-gray-900 mb-4">Get in Touch</h1>
        <p class="text-gray-500">We're here to help! Send us a message and we'll respond as soon as possible.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
        <!-- Contact Info -->
        <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Information</h3>
            <ul class="space-y-6 text-sm text-gray-600">
                <li class="flex items-start gap-4">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 mb-1">Our Location</p>
                        <p>123 Shopping Ave, Dhaka 1200, Bangladesh</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 mb-1">Phone Number</p>
                        <p>+880 1700-000000</p>
                        <p class="text-xs mt-1 text-gray-400">Sun-Thu: 9AM - 6PM</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 mb-1">Email Address</p>
                        <p>support@Bazario.com.bd</p>
                    </div>
                </li>
            </ul>
        </div>
        
        <!-- Contact Form -->
        <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Send a Message</h3>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">First Name</label>
                        <input type="text" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Last Name</label>
                        <input type="text" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Email Address</label>
                    <input type="email" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Message</label>
                    <textarea rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none transition resize-none"></textarea>
                </div>
                <button type="button" onclick="showToast('Message sent successfully!', 'success')" class="btn-primary w-full py-4 rounded-xl text-sm font-bold shadow-lg mt-2">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
