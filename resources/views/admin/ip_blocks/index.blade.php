@extends('layouts.admin')
@section('title', 'IP Blocking Management')
@section('content')

<div class="min-h-screen bg-[#f8fafc] -m-4 sm:-m-6 lg:-m-8 p-4 sm:p-6 lg:p-8">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
            <span class="w-2 h-8 bg-red-600 rounded-full"></span>
            IP Blocking Management
        </h2>
        <p class="text-sm text-slate-500 mt-2 ml-5">Protect your store by blocking suspicious or malicious IP addresses.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Block Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-8">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-50 dark:bg-red-900/20 rounded-xl flex items-center justify-center">
                            <i data-lucide="shield-off" class="w-5 h-5 text-red-600"></i>
                        </div>
                        Block New IP
                    </h3>
                    
                    <form action="{{ route('admin.ip-blocks.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">IP Address</label>
                            <input type="text" name="ip_address" required placeholder="e.g., 192.168.1.1" 
                                class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white outline-none transition-all dark:text-white">
                            @error('ip_address') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">Reason (Optional)</label>
                            <textarea name="reason" placeholder="e.g., Spamming or malicious activity" 
                                class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white outline-none h-32 transition-all dark:text-white"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-red-600 text-white font-bold py-5 rounded-2xl shadow-xl shadow-slate-900/10 hover:shadow-red-600/20 transition-all flex items-center justify-center gap-3 group active:scale-95">
                            <i data-lucide="lock" class="w-5 h-5 transition-transform group-hover:rotate-12"></i>
                            Confirm & Block IP
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <!-- Blocked List -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-slate-700 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 dark:text-white uppercase text-[10px] tracking-widest">Currently Blocked Addresses</h3>
                <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 text-[10px] font-black rounded-full">{{ count($blockedIps) }} Active</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-50 dark:border-slate-700">
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">IP Address</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Reason</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Date Blocked</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @forelse($blockedIps as $block)
                        <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all">
                            <td class="px-8 py-5 font-mono font-bold text-slate-900 dark:text-white text-xs">{{ $block->ip_address }}</td>
                            <td class="px-8 py-5 text-xs text-slate-500">{{ $block->reason ?: 'No reason provided' }}</td>
                            <td class="px-8 py-5 text-xs text-slate-400">{{ $block->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('admin.ip-blocks.destroy', $block) }}" method="POST" onsubmit="return confirm('Allow this IP again?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 bg-slate-100 hover:bg-green-600 text-slate-400 hover:text-white rounded-xl transition-all flex items-center justify-center group/btn active:scale-90" title="Unblock">
                                        <i data-lucide="unlock" class="w-4 h-4 transition-transform group-hover/btn:-rotate-12"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="shield-check" class="w-8 h-8 text-slate-200"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Security status clear</p>
                                    <p class="text-xs text-slate-400 italic font-medium">No IP addresses currently blocked.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($blockedIps->hasPages())
            <div class="p-8 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-50 dark:border-slate-700">
                {{ $blockedIps->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
