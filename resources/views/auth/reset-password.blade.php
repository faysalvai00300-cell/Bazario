@extends('layouts.app')

@section('title', 'Reset Password - Bazario')

@section('content')
<div class="min-h-[80vh] sm:min-h-screen flex flex-col bg-[#FFFFFF] items-center justify-start sm:justify-center p-6 pt-10 sm:pt-6">
    <!-- Main Card -->
    <div class="w-full max-w-sm bg-[#FDECEC] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 sm:p-10 flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-10 text-center">
            <a href="{{ route('home') }}" class="flex items-center justify-center group">
                <img src="{{ asset('Bazario-logo.png') }}" alt="Bazario Logo" class="h-14 w-auto object-contain">
            </a>
        </div>

        <!-- Header Text -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Reset Password</h1>
            <p class="text-gray-500 text-sm mt-1">Enter your email and new password to reset your account.</p>
        </div>

        <!-- Reset Password Form -->
        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address"
                    class="w-full border border-gray-300 rounded-xl px-4 py-4 text-base focus:ring-2 focus:ring-orange-400 focus:outline-none @error('email') border-red-500 @enderror transition-all h-[58px]">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <input type="password" name="password" required placeholder="New Password"
                    class="w-full border border-gray-300 rounded-xl px-4 py-4 text-base focus:ring-2 focus:ring-orange-400 focus:outline-none @error('password') border-red-500 @enderror transition-all h-[58px]">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <input type="password" name="password_confirmation" required placeholder="Confirm New Password"
                    class="w-full border border-gray-300 rounded-xl px-4 py-4 text-base focus:ring-2 focus:ring-orange-400 focus:outline-none transition-all h-[58px]">
            </div>

            <button type="submit" class="w-full text-white py-4 rounded-xl font-black text-lg shadow-xl transition-all hover:scale-[1.02] active:scale-95 leading-none" style="background-color: #000000 !important;">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
