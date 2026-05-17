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
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-gray-900">New Password</h1>
            <p class="text-gray-500 text-sm mt-1">Set a new secure password for your account.</p>
        </div>

        <!-- Reset Password Form -->
        <form action="{{ route('password.update.phone') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">New Password</label>
                <input type="password" name="password" required autofocus placeholder="Minimum 6 characters"
                    class="w-full bg-[#EBF2FF] rounded-xl px-4 py-4 text-base focus:border-blue-500 focus:outline-none transition-all h-[58px] shadow-sm" style="border: 1px solid #d1d5db !important;">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required placeholder="Re-enter your password"
                    class="w-full bg-[#EBF2FF] rounded-xl px-4 py-4 text-base focus:border-blue-500 focus:outline-none transition-all h-[58px] shadow-sm" style="border: 1px solid #d1d5db !important;">
            </div>

            <button type="submit" class="w-full py-4 rounded-xl shadow-xl hover:shadow-2xl transition-all active:scale-[0.98] mt-4 flex items-center justify-center" style="background-color: #000000 !important;">
                <span class="font-bold text-lg" style="color: #ffffff !important;">Reset Password</span>
            </button>
        </form>
    </div>
</div>
@endsection
