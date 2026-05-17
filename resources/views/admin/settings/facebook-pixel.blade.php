@extends('layouts.admin')
@section('title', 'Facebook Pixel & CAPI')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Facebook Pixel & CAPI Settings</h2>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">
    <!-- Facebook Pixel Box -->
    <div class="bg-white rounded-none border border-gray-200 shadow-md p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.facebook-pixel.update') }}" method="POST" class="h-full flex flex-col">
            @csrf
            <!-- Hidden field to preserve CAPI token when saving Pixel ID -->
            <input type="hidden" name="facebook_access_token" value="{{ old('facebook_access_token', $settings->facebook_access_token ?? '') }}">
            
            <div class="flex-1 space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Facebook Pixel</h3>
                    @if($settings->facebook_pixel_id)
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
                
                <div class="mt-4">
                    <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Facebook Pixel ID</label>
                    <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $settings->facebook_pixel_id ?? '') }}" placeholder="e.g. 123456789012345"
                        class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">Enter your Pixel ID to enable frontend tracking.</p>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-8 py-2.5 rounded-none text-sm font-bold text-white uppercase tracking-wide transition shadow-sm" style="background-color: #2563eb;">Save Pixel ID</button>
            </div>
        </form>
    </div>

    <!-- Conversions API Box -->
    <div class="bg-white rounded-none border border-gray-200 shadow-md p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.facebook-pixel.update') }}" method="POST" class="h-full flex flex-col">
            @csrf
            <!-- Hidden field to preserve Pixel ID when saving CAPI token -->
            <input type="hidden" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $settings->facebook_pixel_id ?? '') }}">
            
            <div class="flex-1 space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Conversions API (CAPI)</h3>
                    @if($settings->facebook_access_token)
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
                
                <div class="mt-4">
                    <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">CAPI Access Token</label>
                    <textarea name="facebook_access_token" rows="3" placeholder="EAAB..."
                        class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm resize-y">{{ old('facebook_access_token', $settings->facebook_access_token ?? '') }}</textarea>
                    <p class="text-[11px] text-gray-500 mt-2 dark:text-gray-400 leading-relaxed">Required for server-side event tracking. Generate from Events Manager.</p>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-8 py-2.5 rounded-none text-sm font-bold text-white uppercase tracking-wide transition shadow-sm" style="background-color: #16a34a;">Save CAPI Token</button>
            </div>
        </form>
    </div>
</div>

<div class="mt-8 max-w-5xl">
    <!-- Ads Manager & Marketing API Box -->
    <div class="bg-white rounded-none border border-gray-200 shadow-md p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.facebook-pixel.update') }}" method="POST">
            @csrf
            <!-- Preserve other fields -->
            <input type="hidden" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $settings->facebook_pixel_id ?? '') }}">
            <input type="hidden" name="facebook_access_token" value="{{ old('facebook_access_token', $settings->facebook_access_token ?? '') }}">
            
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 5.01 3.66 9.17 8.44 9.88v-6.99H7.9v-2.89h2.54V9.8c0-2.51 1.49-3.89 3.77-3.89 1.09 0 2.23.19 2.23.19v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.77l-.44 2.89h-2.33v6.99C18.34 21.17 22 17.01 22 12c0-5.523-4.477-10-10-10z"/></svg>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Facebook Marketing API (Ads Manager Data)</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Facebook App ID</label>
                        <input type="text" name="facebook_app_id" value="{{ old('facebook_app_id', $settings->facebook_app_id ?? '') }}" placeholder="e.g. 567890123456789"
                            class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Facebook App Secret</label>
                        <input type="password" name="facebook_app_secret" value="{{ old('facebook_app_secret', $settings->facebook_app_secret ?? '') }}" placeholder="••••••••••••••••"
                            class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Facebook Ad Account ID</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            act_
                        </span>
                        <input type="text" name="facebook_ad_account_id" value="{{ old('facebook_ad_account_id', $settings->facebook_ad_account_id ?? '') }}" placeholder="123456789"
                            class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    </div>
                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">Enter your Ad Account ID without 'act_' prefix.</p>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-8 py-2.5 rounded-none text-sm font-bold text-white uppercase tracking-wide transition shadow-sm" style="background-color: #4f46e5;">Save Marketing API Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection

