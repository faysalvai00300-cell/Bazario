@extends('layouts.admin')
@section('title', 'Courier Integration Settings')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Courier Integration Settings</h2>
</div>

<form action="{{ route('admin.settings.courier.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        
        <!-- Active Courier Selection -->
        <div class="bg-white border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 lg:col-span-2 rounded-none">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100 dark:text-white dark:border-gray-700 flex items-center gap-2">
                    <i data-lucide="truck" class="w-5 h-5 text-green-500"></i> Primary Courier Selection
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative flex flex-col p-4 border @if(($settings->courier_type ?? 'steadfast') == 'steadfast') border-green-500 bg-green-50 @else border-gray-200 @endif rounded-none cursor-pointer hover:border-green-300 transition dark:border-gray-700">
                        <input type="radio" name="courier_type" value="steadfast" {{ ($settings->courier_type ?? 'steadfast') == 'steadfast' ? 'checked' : '' }} class="absolute top-4 right-4 text-green-500 focus:ring-green-500">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Steadfast Courier</span>
                        <span class="text-xs text-gray-500 mt-1 dark:text-gray-400">Popular and easy integration.</span>
                    </label>
                    
                    <label class="relative flex flex-col p-4 border @if(($settings->courier_type ?? '') == 'redx') border-green-500 bg-green-50 @else border-gray-200 @endif rounded-none cursor-pointer hover:border-green-300 transition dark:border-gray-700">
                        <input type="radio" name="courier_type" value="redx" {{ ($settings->courier_type ?? '') == 'redx' ? 'checked' : '' }} class="absolute top-4 right-4 text-green-500 focus:ring-green-500">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">RedX Courier</span>
                        <span class="text-xs text-gray-500 mt-1 dark:text-gray-400">Efficient logistics support.</span>
                    </label>
                    
                    <label class="relative flex flex-col p-4 border @if(($settings->courier_type ?? '') == 'pathao') border-green-500 bg-green-50 @else border-gray-200 @endif rounded-none cursor-pointer hover:border-green-300 transition dark:border-gray-700">
                        <input type="radio" name="courier_type" value="pathao" {{ ($settings->courier_type ?? '') == 'pathao' ? 'checked' : '' }} class="absolute top-4 right-4 text-green-500 focus:ring-green-500">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Pathao Courier</span>
                        <span class="text-xs text-gray-500 mt-1 dark:text-gray-400">Merchant integration with Store ID.</span>
                    </label>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100 dark:bg-gray-700/50 dark:border-gray-700">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 text-sm font-bold rounded-none shadow-sm transition">Save Configuration</button>
            </div>
        </div>

        <!-- Steadfast Settings -->
        <div class="bg-white border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 rounded-none h-full flex flex-col">
            <div class="p-6 flex-1">
                <h3 class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100 dark:text-white dark:border-gray-700">
                    Steadfast API
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">API Key</label>
                        <input type="text" name="steadfast_api_key" value="{{ old('steadfast_api_key', $settings->steadfast_api_key ?? '') }}" placeholder="Enter Steadfast API Key"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Secret Key</label>
                        <input type="text" name="steadfast_secret_key" value="{{ old('steadfast_secret_key', $settings->steadfast_secret_key ?? '') }}" placeholder="Enter Steadfast Secret Key"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100 dark:bg-gray-700/50 dark:border-gray-700">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 text-sm font-bold rounded-none shadow-sm transition">Save Steadfast</button>
            </div>
        </div>

        <!-- RedX Settings -->
        <div class="bg-white border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 rounded-none h-full flex flex-col">
            <div class="p-6 flex-1">
                <h3 class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100 dark:text-white dark:border-gray-700">
                    RedX API
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">API Token</label>
                        <input type="text" name="redx_api_token" value="{{ old('redx_api_token', $settings->redx_api_token ?? '') }}" placeholder="Enter RedX API Token"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100 dark:bg-gray-700/50 dark:border-gray-700">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 text-sm font-bold rounded-none shadow-sm transition">Save RedX</button>
            </div>
        </div>

        <!-- Pathao Settings -->
        <div class="bg-white border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 lg:col-span-2 rounded-none">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100 dark:text-white dark:border-gray-700">
                    Pathao API
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Client ID</label>
                        <input type="text" name="pathao_client_id" value="{{ old('pathao_client_id', $settings->pathao_client_id ?? '') }}"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Client Secret</label>
                        <input type="text" name="pathao_client_secret" value="{{ old('pathao_client_secret', $settings->pathao_client_secret ?? '') }}"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Username (Merchant Email)</label>
                        <input type="text" name="pathao_username" value="{{ old('pathao_username', $settings->pathao_username ?? '') }}"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Password</label>
                        <input type="password" name="pathao_password" value="{{ old('pathao_password', $settings->pathao_password ?? '') }}"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Store ID</label>
                        <input type="text" name="pathao_store_id" value="{{ old('pathao_store_id', $settings->pathao_store_id ?? '') }}"
                            class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Mode</label>
                        <select name="pathao_is_test" class="w-full border border-gray-200 rounded-none px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            <option value="1" {{ ($settings->pathao_is_test ?? 1) == 1 ? 'selected' : '' }}>Sandbox (Test)</option>
                            <option value="0" {{ ($settings->pathao_is_test ?? 1) == 0 ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100 dark:bg-gray-700/50 dark:border-gray-700">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 text-sm font-bold rounded-none shadow-sm transition">Save Pathao</button>
            </div>
        </div>
    </div>
</form>

@endsection
