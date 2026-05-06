@extends('layouts.admin')

@section('title', 'Instruction & Guide')

@section('content')
<!-- Header Section -->
<div class="mb-8 flex justify-between items-center flex-wrap gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="mr-2 p-2 hover:bg-gray-100 rounded-full transition dark:hover:bg-gray-700" title="Back to Dashboard">
                <i data-lucide="arrow-left" class="w-6 h-6 text-gray-600 dark:text-gray-400"></i>
            </a>
            Instruction & Guide
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-3 py-1 text-[10px] rounded-full font-black bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 uppercase tracking-widest">
                    FMOWEB Official
                </span>
                @php
                    $expiryDate = \Carbon\Carbon::parse('2027-04-28');
                    $isExpired = \Carbon\Carbon::now()->greaterThan($expiryDate);
                @endphp

                @if(!$isExpired)
                    <span class="px-3 py-1 text-[11px] rounded-full font-black text-white uppercase tracking-widest flex items-center gap-1.5 shadow-sm border border-amber-400"
                          style="background-color: #f59e0b !important;">
                        <i data-lucide="crown" class="w-3.5 h-3.5 fill-current"></i> Premium Subscription
                    </span>
                @else
                    @php
                        $siteName = $siteSettings->site_name ?? 'SmartLookBD';
                        $waMessage = "আসসালামু আলাইকুম, আমি {$siteName} ওয়েবসাইট থেকে বলছি। আমি ২৮-০৪-২০২৬ তারিখে সাবস্ক্রিপশন কিনেছিলাম। আমি আরও ১ বছরের জন্য সাপোর্ট রিনিউ করতে চাই।";
                    @endphp
                    <a href="https://wa.me/8801915974832?text={{ urlencode($waMessage) }}" target="_blank"
                       class="px-3 py-1 text-[11px] rounded-full font-black text-white uppercase tracking-widest flex items-center gap-1.5 shadow-sm border border-red-500 animate-pulse hover:scale-105 transition-transform"
                       style="background-color: #ef4444 !important;" title="Click to Renew via WhatsApp">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> Subscription Expired (10,000 TK)
                    </a>
                @endif
            </div>
        </h2>
        <p class="text-gray-500 text-sm mt-1 dark:text-gray-400">ওয়েবসাইট পরিচালনা ও ইমেজ সাইজ গাইডলাইন</p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="https://www.facebook.com/fmoweb0" target="_blank" 
           class="relative group px-6 py-3 rounded-2xl font-black text-sm text-white transition-all duration-300 overflow-hidden shadow-[0_10px_20px_-5px_rgba(79,70,229,0.4)] active:scale-95 flex items-center gap-2.5"
           style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;">
            <!-- Glossy Shine Effect -->
            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
            
            <i data-lucide="facebook" class="w-4 h-4 animate-bounce"></i>
            <span class="relative">Contact FMOWEB</span>

            <style>
                @keyframes shimmer {
                    100% { transform: translateX(100%); }
                }
            </style>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Banner Sizes -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
            <h3 class="font-black text-gray-900 text-lg mb-6 dark:text-white flex items-center gap-2">
                <i data-lucide="layout" class="w-5 h-5 text-[#FF6A00]"></i>
                Banner Image Dimensions
            </h3>
            
            <div class="space-y-6">
                <!-- Hero Banner -->
                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50/50 dark:bg-gray-900/30 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center dark:bg-orange-900/40 dark:text-orange-400">
                            <i data-lucide="monitor" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-md font-black text-gray-900 dark:text-white uppercase tracking-tight">Hero Banner (Main Slider)</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <span class="text-[9px] font-black text-gray-400 uppercase block mb-1 underline decoration-orange-400/50">Desktop (PC)</span>
                            <span class="text-xl font-black text-gray-900 dark:text-white">1920 x 735 <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest ml-1">PX</span></span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <span class="text-[9px] font-black text-gray-400 uppercase block mb-1 underline decoration-orange-400/50">Mobile View</span>
                            <span class="text-xl font-black text-gray-900 dark:text-white">800 x 450 <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest ml-1">PX</span></span>
                        </div>
                    </div>
                </div>

                <!-- Middle Banner -->
                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50/50 dark:bg-gray-900/30 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center dark:bg-blue-900/40 dark:text-blue-400">
                            <i data-lucide="gallery-horizontal" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-md font-black text-gray-900 dark:text-white uppercase tracking-tight">Middle Banner (Promo Slider)</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <span class="text-[9px] font-black text-gray-400 uppercase block mb-1 underline decoration-blue-400/50">Desktop (PC)</span>
                            <span class="text-xl font-black text-gray-900 dark:text-white">1400 x 400 <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest ml-1">PX</span></span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <span class="text-[9px] font-black text-gray-400 uppercase block mb-1 underline decoration-blue-400/50">Mobile View</span>
                            <span class="text-xl font-black text-gray-900 dark:text-white">800 x 300 <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest ml-1">PX</span></span>
                        </div>
                    </div>
                </div>

                <!-- Product & Category Sizes -->
                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50/50 dark:bg-gray-900/30 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center dark:bg-purple-900/40 dark:text-purple-400">
                            <i data-lucide="package" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-md font-black text-gray-900 dark:text-white uppercase tracking-tight">Product & Category Images</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <span class="text-[9px] font-black text-gray-400 uppercase block mb-1 underline decoration-purple-400/50">Product Main Image</span>
                            <span class="text-xl font-black text-gray-900 dark:text-white">1000 x 1000 <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest ml-1">PX</span></span>
                            <p class="text-[10px] text-gray-500 mt-1 font-bold italic">Square (1:1 Ratio) is best for mobile grid view.</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <span class="text-[9px] font-black text-gray-400 uppercase block mb-1 underline decoration-purple-400/50">Category Thumbnail</span>
                            <span class="text-xl font-black text-gray-900 dark:text-white">400 x 400 <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest ml-1">PX</span></span>
                            <p class="text-[10px] text-gray-500 mt-1 font-bold italic">Clear, centered images for circle/box icons.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 dark:bg-gray-800 dark:border-gray-700 transition-colors">
            <h3 class="font-black text-gray-900 text-lg mb-6 dark:text-white flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                প্রয়োজনীয় নির্দেশাবলী (Instructions)
            </h3>
            
            <div class="space-y-5">
                <div class="flex gap-4 p-4 rounded-xl bg-green-50/30 dark:bg-green-900/10 border border-green-100/50 dark:border-green-700/50">
                    <div class="w-7 h-7 rounded-lg bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0 font-black text-xs dark:bg-green-900/30">1</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        ব্যানার তৈরি করার সময় মেইন টেক্সট ইমেজের **সেন্টারে** রাখার চেষ্টা করবেন। এতে বিভিন্ন ডিভাইসে ছবি কাটলেও লেখাগুলো নষ্ট হবে না।
                    </p>
                </div>
                <div class="flex gap-4 p-4 rounded-xl bg-green-50/30 dark:bg-green-900/10 border border-green-100/50 dark:border-green-700/50">
                    <div class="w-7 h-7 rounded-lg bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0 font-black text-xs dark:bg-green-900/30">2</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        ছবি সেভ করার সময় **.webp** বা **.jpg** ফরম্যাটে সেভ করবেন যাতে সাইট দ্রুত লোড হয়।
                    </p>
                </div>
                <div class="flex gap-4 p-4 rounded-xl bg-green-50/30 dark:bg-green-900/10 border border-green-100/50 dark:border-green-700/50">
                    <div class="w-7 h-7 rounded-lg bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0 font-black text-xs dark:bg-green-900/30">3</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        মোবাইলের জন্য আলাদা ইমেজ দিলে সাইটটি প্রফেশনাল দেখাবে। তবে একটি ছবি দিতে চাইলে পিসির ছবিই মোবাইলে দেখাবে।
                    </p>
                </div>
                <div class="flex gap-4 p-4 rounded-xl bg-blue-50/30 dark:bg-blue-900/10 border border-blue-100/50 dark:border-blue-700/50">
                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 font-black text-xs dark:bg-blue-900/30">4</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        ইউজার মেসেজের ভেতর কোনো লেখা <strong class="text-blue-600 dark:text-blue-400">&lt;...&gt;</strong> এর মাঝে লিখলে ইউজারের কাছে সেটি **কপি (Copy)** করার অপশন হিসেবে দেখাবে (যেমন: &lt;SAVE50&gt;)। এটি কুপন কোড বা নম্বর শেয়ার করার জন্য দারুণ।
                    </p>
                </div>
                <div class="flex gap-4 p-4 rounded-xl bg-orange-50/30 dark:bg-orange-900/10 border border-orange-100/50 dark:border-orange-700/50">
                    <div class="w-7 h-7 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0 font-black text-xs dark:bg-orange-900/30">5</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        **Pop Up** ইমেজের জন্য ৬০০ x ৮০০ পিক্সেল (Portrait) সাইজ ব্যবহার করা উত্তম। ইমেজে কোনো লিংক যোগ করলে কাস্টমার সরাসরি সেই প্রোডাক্টে চলে যাবে।
                    </p>
                </div>
                <div class="flex gap-4 p-4 rounded-xl bg-purple-50/30 dark:bg-purple-900/10 border border-purple-100/50 dark:border-purple-700/50">
                    <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 font-black text-xs dark:bg-purple-900/30">6</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        **SEO** এর জন্য প্রতিটি প্রোডাক্টের মেটা টাইটেল এবং কি-ওয়ার্ড (Keywords) সেকশনটি পূরণ করার চেষ্টা করবেন। এতে গুগল সার্চে আপনার ওয়েবসাইটটি সবার আগে আসার সম্ভাবনা বাড়বে।
                    </p>
                </div>
                <div class="flex gap-4 p-4 rounded-xl bg-red-50/30 dark:bg-red-900/10 border border-red-100/50 dark:border-red-700/50">
                    <div class="w-7 h-7 rounded-lg bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 font-black text-xs dark:bg-red-900/30">7</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        অর্ডার কনফার্ম করার পর নিয়মিত **Order Status** (যেমন: Processing, Shipped) পরিবর্তন করবেন। এতে কাস্টমার তার একাউন্ট থেকে সহজেই অর্ডারের লাইভ আপডেট দেখতে পাবে।
                    </p>
                </div>
            </div>
        </div>

        <!-- Pixel & Tracking Guide -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 dark:bg-gray-800 dark:border-gray-700 transition-colors">
            <h3 class="font-black text-gray-900 text-lg mb-6 dark:text-white flex items-center gap-2">
                <i data-lucide="target" class="w-5 h-5 text-blue-500"></i>
                Pixel & Tracking Setup Guide
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Facebook Pixel -->
                <div class="p-6 rounded-2xl border border-blue-50 bg-blue-50/20 dark:bg-gray-900/40 dark:border-blue-900/30">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center dark:bg-blue-900/40 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </div>
                        <h4 class="text-md font-black text-gray-900 dark:text-white uppercase tracking-tight">Facebook Pixel & CAPI</h4>
                    </div>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                        <li class="flex gap-2">
                            <span class="text-blue-500 font-bold">•</span>
                            <span>আপনার <b>Events Manager</b> থেকে ১৫ সংখ্যার <b>Pixel ID</b> টি সংগ্রহ করে সেভ করুন।</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-blue-500 font-bold">•</span>
                            <span>নিখুঁত ট্র্যাকিংয়ের জন্য <b>Settings > Conversions API</b> থেকে <b>Access Token</b> জেনারেট করে বসান।</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-blue-500 font-bold">•</span>
                            <span>এটি সেটআপ করলে কাস্টমার কোন প্রোডাক্ট দেখছে (ViewContent), কার্টে যোগ করছে (AddToCart) এবং কিনছে (Purchase) তা ট্র্যাক হবে।</span>
                        </li>
                    </ul>
                </div>

                <!-- TikTok Pixel -->
                <div class="p-6 rounded-2xl border border-slate-50 bg-slate-50/30 dark:bg-gray-900/40 dark:border-slate-800">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center dark:bg-slate-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.03 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.9-.32-1.9-.39-2.81-.12-.76.24-1.45.65-1.95 1.25-.6.69-.95 1.6-.9 2.52.05 1.24.78 2.4 1.83 3.01.89.51 1.94.7 2.96.53 1.05-.15 2-.73 2.57-1.6.43-.63.63-1.39.63-2.17V.02z"/></svg>
                        </div>
                        <h4 class="text-md font-black text-gray-900 dark:text-white uppercase tracking-tight">TikTok Pixel & API</h4>
                    </div>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                        <li class="flex gap-2">
                            <span class="text-slate-900 dark:text-slate-400 font-bold">•</span>
                            <span>TikTok Ads Manager-এর <b>Assets > Event</b> থেকে পিক্সেল আইডিটি সংগ্রহ করুন।</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-slate-900 dark:text-slate-400 font-bold">•</span>
                            <span>সার্ভার ট্র্যাকিংয়ের জন্য <b>Events API</b> টোকেন জেনারেট করে সেটিংস পেজে সেভ করুন।</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-slate-900 dark:text-slate-400 font-bold">•</span>
                            <span>এর ফলে টিকটক আপনার সাইটের সেলস এবং কাস্টমার অ্যাক্টিভিটি নিখুঁতভাবে ট্র্যাক করতে পারবে।</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Developer Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors text-center">
            <div class="mb-4 flex justify-center">
                <div class="p-0">
                    <img src="{{ asset('fmoweb.png') }}" alt="FMOWEB Logo" class="h-24 w-auto grayscale-0 hover:scale-105 transition-transform duration-500">
                </div>
            </div>
            <h3 class="font-black text-gray-900 text-xl dark:text-white tracking-tight">FMOWEB</h3>
            <p class="text-gray-500 text-xs font-black uppercase tracking-[0.2em] mt-1 dark:text-gray-400">Digital Solutions</p>
            
            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4">Developed by FMOWEB</p>
                <div class="flex flex-col gap-3">
                    <a href="https://wa.me/8801915974832" target="_blank" 
                       style="background-color: #25D366 !important;"
                       class="w-full py-3 px-4 text-white rounded-xl text-xs font-black flex items-center justify-center gap-2 hover:bg-[#1ebc5a] transition-all">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> WHATSAPP SUPPORT
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-2xl p-6 text-gray-900 shadow-sm border border-yellow-200 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-100/50 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="zap" class="w-5 h-5 text-yellow-600 fill-yellow-600"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest text-yellow-700">Admin Tip #1</span>
                </div>
                <h4 class="text-base font-black mb-2 leading-snug text-black">ব্যানারের অপশনগুলো ঠিকমতো ব্যবহার করুন।</h4>
                <p class="text-xs text-gray-800 font-medium leading-relaxed">ব্যানার আপলোড করার সময় <strong class="text-black">Show on PC</strong> এবং <strong class="text-black">Show on Mobile</strong> অপশনগুলো দিয়ে আপনি কন্ট্রোল করতে পারবেন ব্যানারটি কোথায় দেখাবে।</p>
            </div>
        </div>

        <div class="bg-green-50 rounded-2xl p-6 text-gray-900 shadow-sm border border-green-200 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-100/50 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="star" class="w-5 h-5 text-green-600 fill-green-600"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest text-green-700">Admin Tip #2</span>
                </div>
                <h4 class="text-base font-black mb-2 leading-snug text-black">প্রোডাক্ট রিভিউ বাড়ান</h4>
                <p class="text-xs text-gray-800 font-medium leading-relaxed">অর্ডার ডেলিভারি হওয়ার পর কাস্টমারকে কল বা মেসেজ দিয়ে রিভিউ দেওয়ার অনুরোধ করুন। পজিটিভ রিভিউ আপনার সেল ১০ গুণ বাড়িয়ে দিতে পারে।</p>
            </div>
        </div>

        <div class="bg-blue-50 rounded-2xl p-6 text-gray-900 shadow-sm border border-blue-200 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/50 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="ticket" class="w-5 h-5 text-blue-600 fill-blue-600"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-700">Admin Tip #3</span>
                </div>
                <h4 class="text-base font-black mb-2 leading-snug text-black">অফারে কুপন কোড ব্যবহার করুন</h4>
                <p class="text-xs text-gray-800 font-medium leading-relaxed">বিশেষ দিনগুলোতে (যেমন ঈদ বা শুক্রবার) ছোট অংকের ছাড় দিয়ে প্রমো কোড তৈরি করুন। এটি কাস্টমারকে দ্রুত অর্ডার করতে উৎসাহিত করে।</p>
            </div>
        </div>

        <div class="bg-purple-50 rounded-2xl p-6 text-gray-900 shadow-sm border border-purple-200 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100/50 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="message-square" class="w-5 h-5 text-purple-600 fill-purple-600"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest text-purple-700">Admin Tip #4</span>
                </div>
                <h4 class="text-base font-black mb-2 leading-snug text-black">হোয়াটসঅ্যাপ চ্যাট সাপোর্ট</h4>
                <p class="text-xs text-gray-800 font-medium leading-relaxed">কাস্টমার যখন ওয়েবসাইটে কোনো প্রোডাক্ট পছন্দ করে কিন্তু দ্বিধায় থাকে, তখন হোয়াটসঅ্যাপ বাটনটি তাকে আপনার সরাসরি কন্টাক্টে নিয়ে আসে। দ্রুত রিপ্লাই দিলে অর্ডার কনফার্ম হওয়ার চান্স বেড়ে যায়।</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-12 text-center pb-8 border-t border-gray-100 dark:border-gray-700 pt-8">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">Design & System Logic refined for SmartLookBD Excellence</p>
</div>
@endsection
