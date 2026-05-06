@extends('layouts.app')

@section('title', 'Verify OTP - SmartLookBD')

@section('content')
<div class="fixed inset-0 flex flex-col items-center justify-center bg-white z-[99999] p-6 overflow-y-auto">
    <!-- Main Card -->
    <div class="w-full max-w-sm bg-[#FDECEC] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-300 p-8 sm:p-10 flex flex-col auth-card-animate">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-8 text-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('final logo.jpeg') }}" alt="SmartLookBD Logo" class="h-12 w-auto object-contain">
                <span class="brand-logo-font text-gray-900 group-hover:text-black transition-all duration-300" style="font-size: 1.8rem; line-height: 1.2;">
                    SmartLookBD
                </span>
            </a>
        </div>

        <!-- Header Text (Centered) -->
        <div class="mb-6 w-full text-center">
            <h1 class="text-xl font-bold text-gray-900">Verify Code</h1>
            <p class="text-gray-500 text-xs mt-1">Enter the 6-digit code sent to your {{ session('otp_type') == 'phone' ? 'phone' : 'email' }}<br>
                <span class="font-semibold text-gray-900">{{ session('otp_identity') }}</span>
            </p>
        </div>

        <!-- Email Alert (Only for Email) -->
        @if(session('otp_type') === 'email')
        <div class="mb-6 animate-fade-in">
            <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-3 flex items-center justify-center gap-3 text-center">
                <span class="text-lg">📧</span>
                <p class="text-[10px] md:text-xs text-orange-800 font-bold leading-tight">
                    কোড না পেলে আপনার ইমেইলের <span class="underline decoration-orange-300">Spam</span> বা <span class="underline decoration-orange-300">Junk</span> ফোল্ডারটি চেক করুন।
                </p>
            </div>
        </div>
        @endif

        <!-- OTP Form -->
        <form action="{{ $type == 'password_reset' ? route('password.verify') : ($type == 'login' ? route('login.verify') : route('register.verify')) }}" method="POST" class="space-y-6" id="otp-form">
            @csrf
            <div>
                <input type="text" name="otp" id="otp-input" required autofocus placeholder="000 000" maxlength="6"
                    class="w-full bg-[#EBF2FF] rounded-xl px-4 py-4 text-center text-3xl font-black tracking-[0.4em] focus:border-blue-500 focus:outline-none transition-all h-[70px] placeholder-gray-300"
                    style="border: 1px solid #94a3b8 !important;">
                @error('otp') <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p> @enderror
            </div>

            <button type="submit" id="verify-btn" 
                class="w-full py-4 rounded-xl font-bold text-lg shadow-sm transition-all duration-300 transform active:scale-[0.98] border border-gray-200 text-gray-400 bg-white" 
                style="color: #94a3b8; background-color: #ffffff;">
                Verify Code
            </button>
        </form>

        <!-- Resend OTP -->
        <div class="mt-8 text-center" id="resend-section">
            <p class="text-gray-500 text-sm">Didn't receive the code?</p>
            <form action="{{ $type == 'login' ? route('login.resend') : route('register.resend') }}" method="POST" class="mt-2" id="resend-form">
                @csrf
                <button type="submit" id="resend-btn" class="font-bold text-gray-900 hover:underline disabled:text-gray-400 disabled:cursor-not-allowed transition-all">
                    Resend OTP <span id="timer-display" class="font-normal text-gray-400 ml-1"></span>
                </button>
            </form>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const otpInput = document.getElementById('otp-input');
        const verifyBtn = document.getElementById('verify-btn');
        const resendBtn = document.getElementById('resend-btn');
        const timerDisplay = document.getElementById('timer-display');
        let timeLeft = 60;

        // Dynamic Button Color Logic
        otpInput.addEventListener('input', function() {
            // Remove spaces if any
            this.value = this.value.replace(/\D/g, '');
            
            if (this.value.length === 6) {
                // Change to Brand Black
                verifyBtn.style.backgroundColor = "#000000";
                verifyBtn.style.color = "#ffffff";
                verifyBtn.style.borderColor = "#000000";
                verifyBtn.style.cursor = "pointer";
                verifyBtn.classList.add('font-black');
            } else {
                // Reset to White/Gray
                verifyBtn.style.backgroundColor = "#ffffff";
                verifyBtn.style.color = "#94a3b8";
                verifyBtn.style.borderColor = "#e2e8f0";
                verifyBtn.style.cursor = "default";
                verifyBtn.classList.remove('font-black');
            }
        });

        function updateTimer() {
            if (timeLeft <= 0) {
                resendBtn.disabled = false;
                timerDisplay.textContent = "";
                return;
            }
            
            resendBtn.disabled = true;
            timerDisplay.textContent = "(" + timeLeft + "s)";
            timeLeft--;
            setTimeout(updateTimer, 1000);
        }

        updateTimer();
    });
</script>
@endpush
