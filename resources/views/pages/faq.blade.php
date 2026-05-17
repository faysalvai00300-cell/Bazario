@extends('layouts.app')
@section('title', 'Frequently Asked Questions - Bazario')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-black text-gray-900 mb-4">Frequently Asked Questions</h1>
        <p class="text-gray-500">Find answers to common questions about shopping with Bazario.</p>
    </div>
    
    <div class="space-y-4" x-data="{ activeFaq: 0 }">
        <!-- FAQ 1 -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <button @click="activeFaq === 1 ? activeFaq = 0 : activeFaq = 1" class="w-full text-left px-6 py-5 flex items-center justify-between font-bold text-gray-900 focus:outline-none">
                <span>How long does delivery take?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="activeFaq === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="activeFaq === 1" x-collapse>
                <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                    Standard delivery within Dhaka usually takes 1-2 business days. Outside Dhaka, delivery takes 3-5 business days depending on the courier service.
                </div>
            </div>
        </div>

        <!-- FAQ 2 -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <button @click="activeFaq === 2 ? activeFaq = 0 : activeFaq = 2" class="w-full text-left px-6 py-5 flex items-center justify-between font-bold text-gray-900 focus:outline-none">
                <span>What are the payment methods available?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="activeFaq === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="activeFaq === 2" x-collapse>
                <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                    We accept Cash on Delivery (COD), bKash, and Nagad payments for all orders.
                </div>
            </div>
        </div>

        <!-- FAQ 3 -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <button @click="activeFaq === 3 ? activeFaq = 0 : activeFaq = 3" class="w-full text-left px-6 py-5 flex items-center justify-between font-bold text-gray-900 focus:outline-none">
                <span>Can I cancel my order?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="activeFaq === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="activeFaq === 3" x-collapse>
                <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                    You can cancel your order at any time before it is marked as "Shipped" from your Account Details page. Once shipped, the order cannot be canceled, but you can refuse delivery.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
