@extends('layouts.admin')

@section('title', 'Global SEO Settings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Global SEO Settings</h1>
        <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">Manage search engine optimization defaults for your store.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <form action="{{ route('admin.settings.seo.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2 dark:text-white dark:border-gray-700">🔍 Global Meta Config</h3>
            <p class="text-xs text-gray-500 mb-4 dark:text-gray-400">These settings will be used as a fallback if specific pages (like a particular product or category) don't have their own SEO details.</p>

            <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Global Meta Title</label>
                <input type="text" name="seo_meta_title" value="{{ old('seo_meta_title', $settings->seo_meta_title ?? '') }}" placeholder="e.g. SmartLookBD - Premium Online Shopping in Bangladesh"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">The title shown in search engine results globally.</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Global Meta Keywords</label>
                <input type="text" name="seo_meta_keywords" value="{{ old('seo_meta_keywords', $settings->seo_meta_keywords ?? '') }}" placeholder="e.g. online shopping, electronics, fashion, SmartLookBD"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">Comma-separated keywords defining your entire store.</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Global Meta Description</label>
                <textarea name="seo_meta_description" rows="3" placeholder="e.g. Shop the best products at SmartLookBD. Electronics, Fashion, Home & Living and more with amazing deals."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">{{ old('seo_meta_description', $settings->seo_meta_description ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">A short description of your online store for search engines (150-160 characters recommended).</p>
            </div>

            <div class="pt-4 mt-4 border-t border-gray-50 dark:border-gray-700">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2 mb-4 dark:text-white dark:border-gray-700">💻 Custom Header Tags</h3>
                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Custom HTML Tags (Head)</label>
                <textarea name="custom_html_tags" rows="6" placeholder="Paste your <meta>, <script>, or <link> tags here. These will be added to the <head> section of every page."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-mono focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">{{ old('custom_html_tags', $settings->custom_html_tags ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-2 dark:text-gray-500 italic">Example: Google Search Console verification tags, custom fonts, or site-wide CSS. <strong>Note:</strong> Be careful when pasting scripts here as they will load on every page.</p>
            </div>
        </div>

        <div class="pt-8 mt-6 border-t border-gray-100 flex justify-end dark:border-gray-700">
            <button type="submit" class="admin-primary-btn px-8 py-2.5 rounded-xl text-sm font-semibold">Save SEO Settings</button>
        </div>
    </form>
</div>
@endsection
