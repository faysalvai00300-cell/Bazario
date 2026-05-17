@extends('layouts.app')
@section('title', 'Privacy Policy - Bazario')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-black text-gray-900 mb-8 text-center">Privacy Policy</h1>
    
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 prose max-w-none text-gray-600 text-sm leading-relaxed space-y-6">
        <p>Your privacy is important to us. It is Bazario's policy to respect your privacy regarding any information we may collect from you across our website, <a href="{{ route('home') }}" class="text-orange-500 font-medium">{{ config('app.url') }}</a>, and other sites we own and operate.</p>
        
        <h3 class="text-lg font-bold text-gray-900 mt-6">1. Information we collect</h3>
        <p>We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.</p>

        <h3 class="text-lg font-bold text-gray-900 mt-6">2. How we use data</h3>
        <p>We only retain collected information for as long as necessary to provide you with your requested service. What data we store, we’ll protect within commercially acceptable means to prevent loss and theft, as well as unauthorized access, disclosure, copying, use or modification.</p>

        <h3 class="text-lg font-bold text-gray-900 mt-6">3. Third-Party Sharing</h3>
        <p>We don’t share any personally identifying information publicly or with third-parties, except when required to by law or to process your payments securely.</p>
        
        <p class="mt-8 text-xs text-gray-400">Last updated: {{ date('F Y') }}</p>
    </div>
</div>
@endsection
