@extends('layouts.app')

@section('title', 'Sign In - Bazario')

@section('content')
<div class="fixed inset-0 flex flex-col items-center justify-center bg-white z-[99999] p-6 overflow-y-auto">
    <!-- Main Card -->
    <div class="w-full max-w-sm bg-[#FDECEC] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-300 p-8 sm:p-10 flex flex-col auth-card-animate">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-10 text-center">
            <a href="{{ route('home') }}" class="flex items-center justify-center group">
                <img src="{{ asset('Bazario-logo.png') }}" alt="Bazario Logo" class="h-14 w-auto object-contain">
            </a>
        </div>

        <div x-data="{ 
            mode: '{{ old('login_type', $errors->has('email') ? 'email' : ($errors->has('phone') ? 'phone' : 'select')) }}',
            phone: '{{ old('phone') }}',
            email: '{{ old('email') }}',
            remember: false,
            init() {
                const savedIdentity = localStorage.getItem('Bazario_login_identity');
                const savedType = localStorage.getItem('Bazario_login_type');
                if (savedIdentity && savedType) {
                    this.mode = savedType;
                    if (savedType === 'phone') this.phone = savedIdentity;
                    else this.email = savedIdentity;
                    this.remember = true;
                }
            },
            saveIdentity() {
                if (this.remember) {
                    const identity = this.mode === 'phone' ? this.phone : this.email;
                    localStorage.setItem('Bazario_login_identity', identity);
                    localStorage.setItem('Bazario_login_type', this.mode);
                } else {
                    localStorage.removeItem('Bazario_login_identity');
                    localStorage.removeItem('Bazario_login_type');
                }
            }
        }" x-init="init()" x-cloak class="w-full">
            
            <!-- Selection Screen -->
            <div x-show="mode === 'select'" class="animate-fade-in text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
                <p class="text-gray-500 text-sm mb-10">Sign in to continue to your account</p>

                <div class="space-y-4">
                    <button @click="mode = 'phone'" class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-100 p-4 rounded-xl font-bold text-gray-700 transition-all shadow-sm hover:border-blue-400 group h-[65px]">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C10.066 18 2 9.934 2 2z"/></svg>
                        Login with Phone
                    </button>

                    <button @click="mode = 'email'" class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-100 p-4 rounded-xl font-bold text-gray-700 transition-all shadow-sm hover:border-blue-400 group h-[65px]">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        Login with Email
                    </button>
                </div>
            </div>

            <!-- Form Screen -->
            <div x-show="mode !== 'select'" class="animate-fade-in">
                <div class="flex items-center gap-3 mb-8">
                    <button @click="mode = 'select'" class="p-2 hover:bg-gray-100 rounded-full transition-all">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </button>
                    <h1 class="text-2xl font-bold text-gray-900" x-text="mode === 'phone' ? 'Login with Phone' : 'Login with Email'"></h1>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="login_type" :value="mode">
                    
                    <!-- Phone Input -->
                    <div x-show="mode === 'phone'">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Phone Number</label>
                        <div class="flex w-full bg-[#EBF2FF] rounded-xl overflow-hidden h-[58px] focus-within:border-blue-500 transition-all" style="border: 1px solid #94a3b8 !important;">
                            <div class="flex items-center px-4 bg-white text-gray-500 font-bold" style="border-right: 1px solid #94a3b8 !important;">+880</div>
                            <input type="tel" name="phone" x-model="phone" placeholder="1712345678" maxlength="11"
                                autocomplete="username"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="flex-1 bg-transparent border-none px-4 py-4 text-base focus:outline-none focus:ring-0 transition-all placeholder-gray-400"
                                style="border: none !important; outline: none !important; box-shadow: none !important;">
                        </div>
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email Input -->
                    <div x-show="mode === 'email'">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Email Address</label>
                        <input type="email" name="email" x-model="email" placeholder="you@example.com"
                            autocomplete="username"
                            class="w-full bg-[#EBF2FF] rounded-xl px-4 py-4 text-base focus:border-blue-500 focus:outline-none transition-all h-[58px]" style="border: 1px solid #94a3b8 !important;">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            autocomplete="current-password"
                            class="w-full bg-[#EBF2FF] rounded-xl px-4 py-4 text-base focus:border-blue-500 focus:outline-none transition-all h-[58px]" style="border: 1px solid #94a3b8 !important;">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between pb-2">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" x-model="remember" class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                            <span class="text-sm text-gray-500 group-hover:text-gray-900 transition-colors">Remember me</span>
                        </label>
                        <a :href="'{{ route('password.request') }}?mode=' + mode" class="text-sm font-bold text-blue-500 hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" @click="saveIdentity()" class="w-full py-4 rounded-xl shadow-xl hover:shadow-2xl transition-all active:scale-[0.98] flex items-center justify-center" style="background-color: #000000 !important;">
                        <span class="font-bold text-lg" style="color: #ffffff !important;">Sign In</span>
                    </button>
                </form>
            </div>

            <div class="mt-10 text-center border-t border-gray-100 pt-6">
                <p class="text-gray-400 text-[10px] sm:text-xs mb-4">This site is protected by reCAPTCHA and the Google <a href="#" class="underline">Privacy Policy</a> and <a href="#" class="underline">Terms of Service</a> apply.</p>
                <div class="flex flex-col gap-4">
                    <p class="text-gray-500 text-sm">Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 font-bold hover:underline">Create one now</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Links -->
    <div class="mt-8">
        <a href="{{ route('pages.terms-of-service') }}" class="text-[#45b86f] text-sm hover:underline font-medium">Terms of service</a>
    </div>

    <style>
        @keyframes premium-fade-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .auth-card-animate {
            animation: premium-fade-in 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        /* Hide floating widgets and site navigation on auth pages */
        .fb_dialog, .fb-customerchat, #fb-root, .fb_dialog_ripple, .whatsapp-floating-btn, #mobile-bottom-nav, 
        .navbar, header, footer, #main-mobile-navbar, .announcement-bar, #mobile-bottom-nav {
            display: none !important;
        }
    </style>
</div>


@endsection
