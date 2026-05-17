@extends('layouts.app')
@section('title', 'Return Policy - Bazario')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-black text-gray-900 mb-8 text-center">Return & Exchange Policy</h1>
    
    <div class="prose max-w-none text-gray-600 space-y-6">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4">7-Day Free Returns</h3>
            <p>At Bazario, we want you to be completely satisfied with your purchase. If you are not entirely happy, you can return your item within 7 days of delivery for a full refund or exchange.</p>
            
            <h4 class="font-bold text-gray-800 mt-6 mb-3">Conditions for Return:</h4>
            <ul class="list-disc pl-5 space-y-2">
                <li>The item must be unused and in the same condition that you received it.</li>
                <li>It must also be in the original packaging with all tags attached.</li>
                <li>You need to provide the receipt or proof of purchase.</li>
            </ul>

            <h4 class="font-bold text-gray-800 mt-6 mb-3">Non-returnable Items:</h4>
            <ul class="list-disc pl-5 space-y-2">
                <li>Innerwear & Sleepwear</li>
                <li>Perishable goods (e.g., food, flowers)</li>
                <li>Customized products</li>
            </ul>
        </div>
    </div>
</div>
@endsection
