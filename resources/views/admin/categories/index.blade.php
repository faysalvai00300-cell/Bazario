@extends('layouts.admin')
@section('title', 'Categories Management')
@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1 w-full">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white whitespace-nowrap">All Categories</h2>
        <form action="{{ route('admin.categories.index') }}" method="GET" class="w-full max-w-sm">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." 
                    class="w-full border border-gray-200 rounded-none pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50 transition-all">
            </div>
        </form>
    </div>
    <a href="{{ route('admin.categories.create', request()->has('target_page') ? ['target_page' => request('target_page')] : []) }}" class="btn-primary px-4 py-2.5 rounded-none text-sm font-semibold flex items-center justify-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Category
    </a>
</div>

<div class="bg-white rounded-none border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-200">
        <table class="w-full text-left text-sm text-gray-600 min-w-[600px] dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4 font-semibold">Image</th>
                    <th class="px-6 py-4 font-semibold">Category</th>
                    <th class="px-6 py-4 font-semibold">Products</th>
                    <th class="px-6 py-4 font-semibold">Homepage Position</th>
                    <th class="px-6 py-4 font-semibold">Sort Order</th>
                    <th class="px-6 py-4 font-semibold text-center">Gender</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($categories as $category)
            <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/50">
                <td class="px-6 py-4">
                    <div class="w-9 h-9 rounded-none overflow-hidden border border-gray-100 dark:border-gray-700 bg-gray-50 flex items-center justify-center">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                    </div>
                </td>
                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white" style="color: {{ $category->color }}">{{ $category->name }}</td>
                <td class="px-6 py-4 font-medium">{{ $category->products_count }}</td>
                <td class="px-6 py-4">
                    @if($category->target_page)
                        <span class="text-xs px-2 py-1 bg-blue-50 text-blue-600 rounded-none dark:bg-blue-900/20 dark:text-blue-400">
                            Page {{ $category->target_page }} : Box {{ $category->target_box }}
                        </span>
                    @else
                        <span class="text-xs text-gray-400">None</span>
                    @endif
                </td>
                <td class="px-6 py-4">{{ $category->sort_order }}</td>
                <td class="px-6 py-4 text-center">
                    @if($category->target_gender && is_array($category->target_gender))
                        <div class="flex flex-wrap justify-center gap-1">
                            @foreach($category->target_gender as $g)
                                <span class="text-[10px] font-black uppercase px-2 py-1 bg-gray-100 text-gray-700 rounded-none dark:bg-gray-700 dark:text-gray-300">
                                    {{ $g }}
                                </span>
                            @endforeach
                        </div>
                    @elseif($category->target_gender)
                         <span class="text-[10px] font-black uppercase px-2 py-1 bg-gray-100 text-gray-700 rounded-none dark:bg-gray-700 dark:text-gray-300">
                            {{ $category->target_gender }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="w-3 h-3 rounded-none inline-block {{ $category->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2 text-xs">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-none transition dark:text-blue-400 dark:hover:bg-blue-900/20" title="Edit">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-none transition dark:text-red-400 dark:hover:bg-red-900/20" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
