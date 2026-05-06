@extends('layouts.admin')
@section('title', 'Payment Gateway Settings')
@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Payment Gateway Settings</h2>
    <p class="text-sm text-slate-500 mt-1">Configure individual API credentials for your merchant accounts.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
    
    <!-- bKash Configuration Box -->
    <div class="flex flex-col h-full bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 dark:bg-slate-800 dark:border-slate-700">
        <form action="{{ route('admin.settings.payment.update') }}" method="POST" class="flex flex-col h-full">
            @csrf
            @method('PUT')
            <div class="p-8 pb-0 flex flex-col h-full">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50 dark:border-slate-700">
                    <div class="flex items-center gap-4">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <img src="https://download.logo.wine/logo/BKash/BKash-Logo.wine.png" 
                             onerror="this.src='https://raw.githubusercontent.com/faysa1mahamudpo/payment-gateways/main/bkash.png'; this.onerror=null;" 
                             class="w-12 h-12 object-contain rounded-xl">
                    </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">bKash Merchant</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Checkout API</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_bkash_active" value="0">
                        <input type="checkbox" name="is_bkash_active" value="1" {{ old('is_bkash_active', $settings->is_bkash_active ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-12 h-6 bg-slate-100 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-[#D12053]"></div>
                    </label>
                </div>
                
                <div class="space-y-6 flex-grow">
                    <div>
                        <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">App Key</label>
                        <input type="text" name="bkash_app_key" value="{{ old('bkash_app_key', $settings->bkash_app_key ?? '') }}" placeholder="Enter bKash App Key"
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-[#D12053] outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">App Secret</label>
                        <input type="password" name="bkash_app_secret" value="{{ old('bkash_app_secret', $settings->bkash_app_secret ?? '') }}" placeholder="Enter bKash App Secret"
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-[#D12053] outline-none transition-all dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Username</label>
                            <input type="text" name="bkash_username" value="{{ old('bkash_username', $settings->bkash_username ?? '') }}" placeholder="Merchant User"
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-[#D12053] outline-none transition-all dark:text-white">
                        </div>
                        <div>
                            <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Password</label>
                            <input type="password" name="bkash_password" value="{{ old('bkash_password', $settings->bkash_password ?? '') }}" placeholder="Merchant Pass"
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-[#D12053] outline-none transition-all dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 rounded-b-[2rem]">
                    <button type="submit" style="background-color: #D12053;" class="w-full text-white font-bold py-4 rounded-xl transition-all flex items-center justify-center gap-3 active:scale-95 shadow-lg shadow-pink-500/10">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Save bKash Config
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Nagad Configuration Box -->
    <div class="flex flex-col h-full bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 dark:bg-slate-800 dark:border-slate-700">
        <form action="{{ route('admin.settings.payment.update') }}" method="POST" class="flex flex-col h-full">
            @csrf
            @method('PUT')
            <div class="p-8 pb-0 flex flex-col h-full">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50 dark:border-slate-700">
                    <div class="flex items-center gap-4">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <img src="https://download.logo.wine/logo/Nagad/Nagad-Logo.wine.png" 
                             onerror="this.src='https://raw.githubusercontent.com/faysa1mahamudpo/payment-gateways/main/nagad.png'; this.onerror=null;" 
                             class="w-12 h-12 object-contain rounded-xl">
                    </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">Nagad Gateway</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Merchant API</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_nagad_active" value="0">
                        <input type="checkbox" name="is_nagad_active" value="1" {{ old('is_nagad_active', $settings->is_nagad_active ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-12 h-6 bg-slate-100 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-[#F7931E]"></div>
                    </label>
                </div>
                
                <div class="space-y-6 flex-grow">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Merchant ID</label>
                            <input type="text" name="nagad_merchant_id" value="{{ old('nagad_merchant_id', $settings->nagad_merchant_id ?? '') }}" placeholder="Nagad ID"
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition-all dark:text-white">
                        </div>
                        <div>
                            <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Nagad Number</label>
                            <input type="text" name="nagad_merchant_number" value="{{ old('nagad_merchant_number', $settings->nagad_merchant_number ?? '') }}" placeholder="017XXXXXXXX"
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition-all dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Public Key</label>
                        <textarea name="nagad_public_key" placeholder="Paste Nagad Public Key here"
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3 text-[10px] h-[72px] focus:ring-2 focus:ring-orange-500 outline-none transition-all dark:text-white font-mono">{{ old('nagad_public_key', $settings->nagad_public_key ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Private Key</label>
                        <textarea name="nagad_private_key" placeholder="Paste Nagad Private Key here"
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3 text-[10px] h-[72px] focus:ring-2 focus:ring-orange-500 outline-none transition-all dark:text-white font-mono">{{ old('nagad_private_key', $settings->nagad_private_key ?? '') }}</textarea>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 rounded-b-[2rem]">
                    <button type="submit" style="background-color: #F7931E;" class="w-full text-white font-bold py-4 rounded-xl transition-all flex items-center justify-center gap-3 active:scale-95 shadow-lg shadow-orange-500/10">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Save Nagad Config
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- SSLCommerz Configuration Box -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-8 dark:bg-slate-800 dark:border-slate-700 lg:col-span-2">
        <form action="{{ route('admin.settings.payment.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50 dark:border-slate-700">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white">
                        <i data-lucide="shield-check" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">SSLCommerz Gateway</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Visa / Master / Cards</p>
                    </div>
                </div>
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Sandbox</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="ssl_is_test" value="0">
                            <input type="checkbox" name="ssl_is_test" value="1" {{ old('ssl_is_test', $settings->ssl_is_test ?? true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-100 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_ssl_active" value="0">
                        <input type="checkbox" name="is_ssl_active" value="1" {{ old('is_ssl_active', $settings->is_ssl_active ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-12 h-6 bg-slate-100 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-end">
                <div>
                    <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Store ID</label>
                    <input type="text" name="ssl_store_id" value="{{ old('ssl_store_id', $settings->ssl_store_id ?? '') }}" placeholder="Enter SSL Store ID"
                        class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all dark:text-white">
                </div>
                <div>
                    <label class="text-[11px] font-black uppercase text-slate-400 mb-3 block tracking-widest">Store Password</label>
                    <input type="password" name="ssl_store_password" value="{{ old('ssl_store_password', $settings->ssl_store_password ?? '') }}" placeholder="Enter Store Password"
                        class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all dark:text-white">
                </div>
            <div class="mt-10 p-8 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 rounded-b-[2rem]">
                <button type="submit" style="background-color: #2563eb;" class="w-full text-white font-bold py-5 rounded-xl shadow-lg shadow-blue-500/10 transition-all flex items-center justify-center gap-3 active:scale-95">
                    <i data-lucide="save" class="w-6 h-6"></i>
                    Save SSLCommerz Configuration
                </button>
            </div>
            </div>
        </form>
    </div>
</div>

@endsection
