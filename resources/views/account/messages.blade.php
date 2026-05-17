@extends('layouts.app')

@section('title', 'Inbox - Bazario')

@section('content')
<style>
    .custom-dashboard-wrapper {
        background-color: #F4F4F6;
        min-height: 100vh;
        padding: 2rem 1rem;
    }
    .custom-dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .custom-dashboard-layout {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .custom-dashboard-sidebar {
        width: 100%;
    }
    .custom-dashboard-main {
        width: 100%;
    }

    @media (min-width: 1024px) {
        .custom-dashboard-layout {
            flex-direction: row;
            align-items: flex-start;
        }
        .custom-dashboard-sidebar {
            width: 320px;
            flex-shrink: 0;
        }
        .custom-dashboard-main {
            flex: 1;
            min-width: 0;
        }
    }

    /* Inbox List Styling */
    .inbox-card {
        background: white;
        border-radius: 2rem; /* Added border radius back */
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }
    .inbox-item-row {
        padding: 1.25rem 1.5rem; /* Reduced padding for mobile */
        border-bottom: 1px solid #f8fafc;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 1rem; /* Smaller gap for mobile */
    }
    @media (min-width: 768px) {
        .inbox-item-row {
            padding: 1.75rem 2.5rem;
            gap: 1.5rem;
        }
    }
    .inbox-item-row:hover {
        background-color: #f8fafc;
        transform: translateX(10px);
    }
    .inbox-item-row.unread {
        border-left: 5px solid #FF6A00;
        background-color: #fff;
    }
    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 1.25rem;
        background-color: #f8fafc;
        border: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e0;
        flex-shrink: 0;
    }
    .inbox-item-row:hover .icon-box {
        background-color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        color: #FF6A00;
    }

    /* --- HYPER-REALISTIC WIDE ENVELOPE MODAL --- */
    .envelope-modal {
        position: fixed;
        inset: 0;
        z-index: 2147483646; /* Slightly lower than confetti */
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
    }
    
    .envelope-container {
        position: relative;
        width: 500px;
        height: 260px;
        perspective: 1500px;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Mobile Scaling for Envelope - Balanced Size */
    @media (max-width: 600px) {
        .envelope-container {
            transform: scale(0.75);
        }
    }
    @media (max-width: 450px) {
        .envelope-container {
            transform: scale(0.65);
        }
    }
    @media (max-width: 380px) {
        .envelope-container {
            transform: scale(0.55);
        }
    }

    .envelope {
        position: relative;
        width: 100%;
        height: 100%;
        background: #fdfdfd;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
    }

    /* Back of the envelope */
    .envelope-back {
        position: absolute;
        inset: 0;
        background: #e2e8f0; /* Darker inside to show depth */
        border-radius: 0 0 12px 12px;
        z-index: 1;
        box-shadow: inset 0 10px 20px rgba(0,0,0,0.05);
        transition: transform 0.8s ease 1.0s, opacity 0.6s ease 1.0s;
    }
    
    .envelope-container.open .envelope-back {
        opacity: 0;
        transform: translateY(80px);
    }

    /* THE LETTER (Deep inside the pocket) */
    .letter {
        position: absolute;
        bottom: 10px; 
        left: 15px;
        width: 470px;
        height: 260px; /* Reduced height to remove extra gap */
        background: #fdfcf8; /* Subtle realistic cream paper */
        padding: 25px; /* Reduced padding for a tighter look */
        z-index: 2; /* Between back and front folds */
        
        border: 1px solid #e9e5df;
        border-radius: 4px;
        box-shadow: inset 0 0 40px rgba(0,0,0,0.02), 0 2px 10px rgba(0,0,0,0.02);
    }

    .envelope-container.open .letter {
        animation: letterEmergeAndCenter 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        animation-delay: 0.3s;
    }
    
    @keyframes letterEmergeAndCenter {
        0% { transform: translateY(0) scale(1); z-index: 2; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        35% { transform: translateY(-240px) scale(1.02); z-index: 2; box-shadow: 0 15px 35px rgba(0,0,0,0.15); }
        36% { z-index: 20; }
        50% { transform: translateY(-240px) scale(1.05); z-index: 20; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        100% { transform: translateY(-20px) scale(1.25); z-index: 20; box-shadow: 0 40px 100px -15px rgba(0,0,0,0.4); }
    }

    /* FRONT POCKET (Transparent container with white folds) */
    .envelope-front-pocket {
        position: absolute;
        inset: 0;
        z-index: 4; /* Higher than letter to hide it behind the folds */
        pointer-events: none;
        overflow: hidden;
        border-radius: 0 0 12px 12px;
        transition: transform 0.8s ease 1.0s, opacity 0.6s ease 1.0s;
    }
    
    .envelope-container.open .envelope-front-pocket {
        opacity: 0;
        transform: translateY(80px);
    }
    
    /* Decoration folds */
    .envelope-front-pocket::before {
        content: '';
        position: absolute;
        inset: 0;
        border-left: 250px solid #f1f5f9; /* Slightly darker than front */
        border-right: 250px solid #f1f5f9;
        border-top: 160px solid transparent;
        border-bottom: 160px solid #f1f5f9;
        filter: drop-shadow(0 0 5px rgba(0,0,0,0.02));
    }
    
    /* Bottom triangle fold */
    .envelope-front-pocket::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 0;
        border-left: 250px solid transparent;
        border-right: 250px solid transparent;
        border-bottom: 190px solid #ffffff; /* Brightest white */
        filter: drop-shadow(0 -5px 15px rgba(0,0,0,0.1)); /* Stronger shadow to make line visible */
    }

    /* Top Flap */
    .flap {
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 0;
        border-left: 250px solid transparent;
        border-right: 250px solid transparent;
        border-top: 180px solid #ffffff;
        z-index: 5; 
        transform-origin: top;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.6s ease 1.0s;
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.15)); /* Stronger shadow for clear line */
    }

    .envelope-container.open .flap {
        transform: rotateX(180deg); /* JUST rotate, no vertical shifting */
        opacity: 0;
        z-index: 0; 
        filter: drop-shadow(0 -5px 15px rgba(0,0,0,0.1));
    }

    /* Wax Seal */
    .wax-seal {
        position: absolute;
        top: 180px;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 55px;
        height: 55px;
        background: radial-gradient(circle at 30% 30%, #ff4b4b, #cc0000, #880000);
        border-radius: 50%;
        z-index: 6;
        box-shadow: 0 4px 8px rgba(0,0,0,0.4), inset 0 2px 4px rgba(255,255,255,0.4);
        transition: opacity 0.4s ease, transform 0.6s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffcccc;
        font-family: serif;
        font-weight: bold;
        font-size: 28px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .wax-seal::after {
        content: '';
        position: absolute;
        inset: 3px;
        border-radius: 50%;
        border: 1px dashed rgba(255,255,255,0.3);
    }

    .envelope-container.open .wax-seal {
        opacity: 0;
        transform: translate(-50%, -50%) scale(1.5);
        pointer-events: none;
    }

    /* Letter Content */
    .letter-content {
        opacity: 0;
        transition: opacity 0.6s ease 1.2s;
        background: transparent;
        padding: 10px 20px;
        /* Box shadow removed to let lines show through subtly */
    }
    .envelope-container.open .letter-content {
        opacity: 1;
    }

    .close-overlay {
        position: absolute;
        inset: 0;
        cursor: pointer;
    }
    .close-btn {
        position: absolute;
        top: -60px;
        right: -60px;
        background: rgba(255,255,255,0.1);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: 0.3s;
    }
    .close-btn:hover {
        background: #FF3B30;
        transform: rotate(90deg);
    }
    .copyable-code-wrapper {
        display: inline-flex;
        align-items: center;
        background: #f8fafc;
        border: 2px dashed #e2e8f0;
        padding: 4px 10px;
        border-radius: 10px;
        margin: 4px 0;
        cursor: pointer;
        transition: all 0.2s;
        gap: 8px;
        color: #FF6A00;
        font-family: monospace;
        font-weight: 800;
        position: relative;
    }
    .copyable-code-wrapper:hover {
        border-color: #FF6A00;
        background: #fff5ed;
        transform: translateY(-1px);
    }
    .copy-btn-icon {
        background: #FF6A00;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    .copy-success-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #10b981;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-family: sans-serif;
        pointer-events: none;
        opacity: 0;
        transition: 0.2s;
    }
    .copyable-code-wrapper.copied .copy-success-tooltip {
        opacity: 1;
        bottom: 110%;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<div class="custom-dashboard-wrapper" x-data="{ 
        isOpen: false,
        selectedMessage: null,
        isAnimating: false,
        triggerConfetti(msg) {
            if (typeof confetti === 'undefined') return;
            // Check gift status directly from the passed message
            const hasGift = msg && (msg.has_gift == 1 || msg.has_gift === true);
            if (hasGift) {
                setTimeout(() => {
                    var duration = 5 * 1000;
                    var animationEnd = Date.now() + duration;
                    var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 2147483647 };

                    function randomInRange(min, max) {
                        return Math.random() * (max - min) + min;
                    }

                    var interval = setInterval(function() {
                        var timeLeft = animationEnd - Date.now();

                        if (timeLeft <= 0) {
                            return clearInterval(interval);
                        }

                        var particleCount = 150 * (timeLeft / duration);
                        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
                    }, 250);
                }, 1000);
            }
        }
    }">
    <div class="custom-dashboard-container">
        <div class="custom-dashboard-layout">
            
            <!-- Sidebar (Hidden on mobile, matching Orders page style focus) -->
            <aside class="custom-dashboard-sidebar hidden md:block">
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="w-24 h-24 flex-shrink-0 aspect-square rounded-full bg-orange-50 flex items-center justify-center text-[#FF6A00] mb-4 ring-8 ring-orange-50/50 relative border-2 border-orange-100 mx-auto">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ auth()->user()->name }}</h2>
                        <p class="text-sm font-bold text-gray-400 mt-1">{{ auth()->user()->email ?? auth()->user()->phone }}</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('account.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-bold text-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-bold text-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            My Orders
                        </a>
                        <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-6 py-4 rounded-2xl bg-[#FF6A00] text-white font-black text-sm shadow-lg shadow-orange-500/20">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Messages
                            </div>
                            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                <span class="bg-white text-[#FF6A00] text-[10px] font-black px-2 py-0.5 rounded-full">{{ $unreadMessagesCount }}</span>
                            @endif
                        </a>
                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-[#FF3B30] hover:bg-red-50 font-bold text-sm transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="custom-dashboard-main">
                
                <!-- Header Section (Matching Orders Page) -->
                <div class="mb-8 md:hidden">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter mb-4">Inbox</h1>
                    <nav class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Home</a>
                        <span class="text-gray-300">/</span>
                        <a href="{{ route('account.dashboard') }}" class="hover:text-orange-600 transition-colors">Profile</a>
                        <span class="text-gray-300">/</span>
                        <span class="text-orange-600">Inbox</span>
                    </nav>
                </div>

                <div class="inbox-card">
                    <div class="pt-8 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="px-6 pb-4 text-xs font-black text-gray-400 uppercase tracking-widest">Your Messages</h3>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @forelse($messages as $message)
                        <div class="inbox-item-row {{ $message->is_read ? '' : 'unread' }}" 
                             id="msg-row-{{ $message->id }}"
                             @click="openMessage(@js($message))">
                            
                            <div class="icon-box relative shrink-0">
                                <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                @if(!$message->is_read)
                                <span class="unread-dot absolute -top-1 -right-1 w-3 h-3 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                                @endif
                            </div>
                            
                            <div class="msg-info flex-1 min-w-0">
                                <h4 class="msg-title text-sm md:text-base font-black text-gray-900 truncate">
                                    {{ $message->sender ? 'Support Update from ' . $message->sender->name : 'Support Update' }}
                                </h4>
                                <div class="flex items-center gap-2 md:gap-3">
                                    <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest flex-shrink-0">
                                        {{ $message->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="msg-preview text-xs md:text-sm text-gray-500 truncate flex-1">
                                        {{ $message->message }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center shrink-0">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center hover:bg-[#FF6A00] transition-all">
                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-20 text-center text-gray-400">Your inbox is empty</div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- HYPER-REALISTIC WIDE ENVELOPE MODAL -->
    <div class="envelope-modal" x-show="isOpen" x-cloak x-transition.opacity>
        <div class="close-overlay" @click="isOpen = false; isAnimating = false;"></div>
        <div class="relative">
            
            <div class="envelope-container" :class="isAnimating ? 'open' : ''">
                <div class="envelope">
                    <div class="envelope-back"></div>
                    
                    <!-- THE LETTER -->
                    <div class="letter">
                        <div class="letter-content">
                            <div class="mb-4 pb-3 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <h5 class="text-black font-black uppercase text-[13px] tracking-[0.15em]">Bazario</h5>
                                        <!-- Verified Blue Tick -->
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-bold" x-text="selectedMessage ? new Date(selectedMessage.created_at).toLocaleString() : ''"></p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center">
                                    <img src="{{ asset('Bazario-logo.png') }}" class="w-7 h-7 rounded-full opacity-60" alt="">
                                </div>
                            </div>
                            <div class="text-[16px] text-gray-800 leading-relaxed font-medium whitespace-pre-line max-h-[160px] overflow-y-auto pr-3 custom-scrollbar" 
                                 x-html="selectedMessage ? formatMessage(selectedMessage.message) : ''"></div>
                        </div>
                    </div>
                    
                    <!-- Front Pocket Folds -->
                    <div class="envelope-front-pocket"></div>
                    
                    <!-- Top Flap -->
                    <div class="flap"></div>
                    
                    <!-- Wax Seal -->
                    <div class="wax-seal">S</div>
                </div>
            </div>
            
            <div class="close-btn" @click="isOpen = false; document.querySelector('.envelope-container').classList.remove('open')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
        </div>
    </div>
</div>
    <script>
        function openMessage(msg) {
            // Access Alpine data
            const el = document.querySelector('.custom-dashboard-wrapper');
            if (!el) return;
            const data = Alpine.$data(el);
            
            data.selectedMessage = msg;
            data.isOpen = true;
            
            if (!msg.is_read) {
                msg.is_read = 1;
                // Update UI immediately
                const row = document.getElementById('msg-row-' + msg.id);
                if (row) {
                    row.classList.remove('unread');
                    const dot = row.querySelector('.unread-dot');
                    if (dot) dot.remove();
                }
                
                fetch(`/messages/${msg.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
            }
            
            data.triggerConfetti(msg);
            
            // Ensure the envelope opens using Alpine state
            setTimeout(() => {
                data.isAnimating = true;
            }, 300);
        }

        function formatMessage(text) {
            if (!text) return '';
            // Match text inside < >
            return text.replace(/<([^>]+)>/g, (match, code) => {
                return `<span class="copyable-code-wrapper" onclick="copyCode(this, '${code.replace(/'/g, "\\'")}')">
                    <span class="copy-success-tooltip">Copied!</span>
                    ${code}
                    <span class="copy-btn-icon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </span>
                </span>`;
            });
        }

        function copyCode(el, code) {
            navigator.clipboard.writeText(code).then(() => {
                el.classList.add('copied');
                setTimeout(() => el.classList.remove('copied'), 2000);
            });
        }
    </script>
@endsection
