@extends('layouts.admin')
@section('title', 'Google Ads Settings')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Google Ads API Settings</h2>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">
    <!-- Google Ads Credentials Box -->
    <div class="bg-white rounded-none border border-gray-200 shadow-md p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors md:col-span-2">
        <form action="{{ route('admin.google-ads.update') }}" method="POST" class="h-full flex flex-col">
            @csrf
            
            <div class="flex-1 space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#EA4335] text-white p-2 rounded-lg">
                            <i data-lucide="globe" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Google Ads API Credentials</h3>
                    </div>
                    @if($settings->google_ads_developer_token ?? false)
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-800 text-[10px] font-black uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Active
                        </span>
                    @else
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-100 text-red-800 text-[10px] font-black uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Inactive
                        </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Client ID</label>
                        <input type="text" name="google_ads_client_id" value="{{ old('google_ads_client_id', $settings->google_ads_client_id ?? '') }}" placeholder="Enter Google Client ID"
                            class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Client Secret</label>
                        <input type="password" name="google_ads_client_secret" value="{{ old('google_ads_client_secret', $settings->google_ads_client_secret ?? '') }}" placeholder="Enter Google Client Secret"
                            class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Developer Token</label>
                        <input type="text" name="google_ads_developer_token" value="{{ old('google_ads_developer_token', $settings->google_ads_developer_token ?? '') }}" placeholder="Enter Developer Token"
                            class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Manager Account ID (MCC)</label>
                        <input type="text" name="google_ads_manager_id" value="{{ old('google_ads_manager_id', $settings->google_ads_manager_id ?? '') }}" placeholder="e.g. 123-456-7890"
                            class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">These credentials are required to fetch real-time analytics from Google Ads API. Make sure your Google Cloud project has the Google Ads API enabled.</p>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-10 py-3 rounded-none text-sm font-black text-white uppercase tracking-widest transition-all shadow-lg hover:shadow-blue-500/40 transform active:scale-95 flex items-center justify-center gap-2" style="background-color: #4285F4 !important;">
                    <i data-lucide="save" class="w-4 h-4"></i> SAVE CREDENTIALS
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
