@extends('layouts.app')
@section('title', 'Terms of Service - Bazario')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-black text-gray-900 mb-8 text-center">Terms of Service</h1>
    
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 prose max-w-none text-gray-600 text-sm leading-relaxed space-y-6">
        <p>Welcome to Bazario. By accessing this website, you agree to be bound by these Terms of Service, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws.</p>
        
        <h3 class="text-lg font-bold text-gray-900 mt-6">1. Acceptance of Terms</h3>
        <p>If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this website are protected by applicable copyright and trademark law.</p>

        <h3 class="text-lg font-bold text-gray-900 mt-6">2. Use License</h3>
        <p>Permission is granted to temporarily download one copy of the materials (information or software) on Bazario's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title.</p>

        <h3 class="text-lg font-bold text-gray-900 mt-6">3. Disclaimer</h3>
        <p>The materials on Bazario's website are provided on an 'as is' basis. Bazario makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>

        <h3 class="text-lg font-bold text-gray-900 mt-6">4. Limitations</h3>
        <p>In no event shall Bazario or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Bazario's website.</p>
        
        <h3 class="text-lg font-bold text-gray-900 mt-6">5. Revisions and Errata</h3>
        <p>The materials appearing on Bazario's website could include technical, typographical, or photographic errors. Bazario does not warrant that any of the materials on its website are accurate, complete, or current. Bazario may make changes to the materials contained on its website at any time without notice.</p>

        <p class="mt-8 text-xs text-gray-400">Last updated: {{ date('F Y') }}</p>
    </div>
</div>
@endsection
