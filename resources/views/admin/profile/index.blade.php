@extends('layouts.admin')
@section('title', 'Admin Profile Settings')
@section('content')

<div class="mb-8 text-center">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Profile Settings</h2>
    <p class="text-sm text-gray-500 mt-1">Change your admin login credentials and account information.</p>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border-2 border-gray-200 shadow-xl p-8 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Full Name</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 mt-3 w-4 h-4 text-gray-400"></i>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required 
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Login Email (Username)</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3 top-1/2 -translate-y-1/2 mt-3 w-4 h-4 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required 
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-50 dark:border-gray-700">
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4 text-orange-500"></i> Change Password
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">New Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Confirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-orange-500/20 group">
                    <i data-lucide="save" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                    Update Profile Credentials
                </button>
            </div>
        </form>
    </div>

    @if(!$user)
    <div class="mt-6 p-4 bg-orange-50 border border-orange-100 rounded-xl">
        <div class="flex gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-500"></i>
            <div>
                <p class="text-sm font-bold text-orange-800">No Admin User found in Database</p>
                <p class="text-xs text-orange-700 mt-1">You are currently logged in via environment configuration. Please ensure you have an admin user in the database to use this feature fully.</p>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
