@extends('layouts.admin')
@section('title', 'TikTok Pixel & Events API')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">TikTok Pixel & Events API Settings</h2>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">
    <!-- TikTok Pixel Box -->
    <div class="bg-white rounded-none border border-gray-200 shadow-md p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.tiktok-pixel.update') }}" method="POST" class="h-full flex flex-col">
            @csrf
            <!-- Hidden field to preserve TikTok Access Token when saving Pixel ID -->
            <input type="hidden" name="tiktok_access_token" value="{{ old('tiktok_access_token', $settings->tiktok_access_token ?? '') }}">
            
            <div class="flex-1 space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="bg-black text-white p-2 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.03 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.9-.32-1.9-.39-2.81-.12-.76.24-1.45.65-1.95 1.25-.6.69-.95 1.6-.9 2.52.05 1.24.78 2.4 1.83 3.01.89.51 1.94.7 2.96.53 1.05-.15 2-.73 2.57-1.6.43-.63.63-1.39.63-2.17V.02z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">TikTok Pixel</h3>
                    </div>
                    @if($settings->tiktok_pixel_id ?? false)
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
                    <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">TikTok Pixel ID</label>
                    <input type="text" name="tiktok_pixel_id" value="{{ old('tiktok_pixel_id', $settings->tiktok_pixel_id ?? '') }}" placeholder="e.g. CCO7O7RC77B6G..."
                        class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-black focus:border-black focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm">
                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">Enter your TikTok Pixel ID to enable browser-side tracking.</p>
                </div>
            </div>

            <div class="pt-6 mt-auto border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-10 py-3 rounded-none text-sm font-black text-white uppercase tracking-widest transition-all shadow-lg hover:shadow-orange-500/40 transform active:scale-95 flex items-center justify-center gap-2" style="background-color: #FF6A00 !important;">
                    <i data-lucide="save" class="w-4 h-4"></i> SAVE PIXEL ID
                </button>
            </div>
        </form>
    </div>

    <!-- TikTok Events API Box -->
    <div class="bg-white rounded-none border border-gray-200 shadow-md p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.tiktok-pixel.update') }}" method="POST" class="h-full flex flex-col">
            @csrf
            <!-- Hidden field to preserve TikTok Pixel ID when saving Events API token -->
            <input type="hidden" name="tiktok_pixel_id" value="{{ old('tiktok_pixel_id', $settings->tiktok_pixel_id ?? '') }}">
            
            <div class="flex-1 space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#EE1D52] text-white p-2 rounded-lg text-xs font-black uppercase">
                            API
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Events API</h3>
                    </div>
                    @if($settings->tiktok_access_token ?? false)
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
                    <label class="text-sm font-semibold text-gray-700 mb-2 block dark:text-gray-300">Access Token (Server-Side)</label>
                    <textarea name="tiktok_access_token" rows="3" placeholder="Enter Access Token..."
                        class="w-full rounded-none border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-black focus:border-black focus:outline-none dark:bg-gray-900 dark:border-gray-600 dark:text-white shadow-sm resize-y">{{ old('tiktok_access_token', $settings->tiktok_access_token ?? '') }}</textarea>
                    <p class="text-[11px] text-gray-500 mt-2 dark:text-gray-400 leading-relaxed">Required for server-side tracking. Generate from TikTok Ads Manager.</p>
                </div>
            </div>

            <div class="pt-6 mt-auto border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-10 py-3 rounded-none text-sm font-black text-white uppercase tracking-widest transition-all shadow-lg hover:shadow-green-500/40 transform active:scale-95 flex items-center justify-center gap-2" style="background-color: #10b981 !important;">
                    <i data-lucide="save" class="w-4 h-4"></i> SAVE TOKEN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
