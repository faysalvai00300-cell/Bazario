@extends('layouts.admin')
@section('title', 'Products Management')
@section('content')

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <form action="{{ route('admin.products.index') }}" method="GET"
            class="flex flex-col sm:flex-row gap-3 w-full lg:flex-1">
            <div class="relative group flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search"
                        class="w-4 h-4 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50 transition-all">
            </div>

            <select name="target_page" onchange="this.form.submit()"
                class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50 transition-all min-w-[150px]">
                <option value="">All Pages</option>
                <option value="1" {{ request('target_page') == 1 ? 'selected' : '' }}>Category Page 1</option>
                <option value="2" {{ request('target_page') == 2 ? 'selected' : '' }}>Category Page 2</option>
                <option value="3" {{ request('target_page') == 3 ? 'selected' : '' }}>Category Page 3</option>
                <option value="4" {{ request('target_page') == 4 ? 'selected' : '' }}>Category Page 4</option>
                <option value="5" {{ request('target_page') == 5 ? 'selected' : '' }}>Category Page 5</option>
            </select>

            <select name="category_id" onchange="this.form.submit()"
                class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50 transition-all min-w-[150px]">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="flex items-center gap-2">
            <div id="bulk-delete-btn-wrapper">
                <button type="button" id="bulk-delete-btn" onclick="confirmBulkDelete()" disabled title="Bulk Delete"
                    class="bg-gray-100 text-gray-400 px-5 py-2.5 rounded-xl transition-all border border-gray-200 cursor-not-allowed flex items-center gap-2 font-bold text-sm relative">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                    <span id="selected-count"
                        class="hidden absolute -top-2 -right-2 bg-red-600 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-lg">0</span>
                </button>
            </div>
            <a href="{{ route('admin.products.create', ['category_id' => request('category_id'), 'target_page' => request('target_page')]) }}"
                class="bg-[#FF6A00] hover:bg-[#FF7A1A] text-white px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-orange-500/20 flex items-center gap-2 font-bold text-sm whitespace-nowrap">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Product
            </a>
        </div>
    </div>

    <form id="bulk-delete-form" action="{{ route('admin.products.bulk-delete') }}" method="POST" class="hidden">
        @csrf
    </form>

    <div
        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-200">
            <table class="w-full text-left text-sm text-gray-600 min-w-[750px] dark:text-gray-300">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-4 font-semibold w-10">
                            <input type="checkbox" id="select-all"
                                class="rounded border-gray-300 text-orange-500 focus:ring-orange-500 w-4 h-4 cursor-pointer">
                        </th>
                        <th class="px-4 py-4 font-semibold">Product</th>
                        <th class="px-6 py-4 font-semibold hidden md:table-cell">Category</th>
                        <th class="px-4 py-4 font-semibold">Price</th>
                        <th class="px-4 py-4 font-semibold">Stock</th>
                        <th class="px-4 py-4 font-semibold text-center">Status</th>
                        <th class="px-4 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/50">
                            <td class="px-4 py-4">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                    class="product-checkbox rounded border-gray-300 text-orange-500 focus:ring-orange-500 w-4 h-4 cursor-pointer">
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->thumbnail_url }}"
                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg object-cover bg-gray-100 dark:bg-gray-700 cursor-zoom-in hover:opacity-80 transition"
                                         @click="$store.imageModal.open('{{ $product->thumbnail_url }}')">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $product->slug) }}" target="_blank"
                                            class="font-bold text-gray-900 hover:text-[#FF6A00] dark:text-white dark:hover:text-orange-400 truncate block">{{ $product->name }}</a>
                                        <p class="text-[10px] text-gray-400 mt-0.5 truncate hidden sm:block">{{ $product->sku }}
                                            | {{ $product->brand }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold"
                                    style="background: {{ $product->category->color }}15; color: {{ $product->category->color }}">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">
                                    Tk{{ number_format($product->effective_price) }}</div>
                                @if($product->effective_price < $product->price)
                                    <div class="text-xs text-gray-400 line-through">Tk{{ number_format($product->price) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->stock > 10)
                                    <span
                                        class="text-green-600 font-bold bg-green-50 px-2.5 py-1 rounded-lg dark:bg-green-900/20 dark:text-green-400">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span
                                        class="text-orange-500 font-bold bg-orange-50 px-2.5 py-1 rounded-lg dark:bg-orange-900/20 dark:text-orange-400">{{ $product->stock }}</span>
                                @else
                                    <span
                                        class="text-red-500 font-bold bg-red-50 px-2.5 py-1 rounded-lg dark:bg-red-900/20 dark:text-red-400">Out</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span
                                    class="w-2.5 h-2.5 rounded-full inline-block {{ $product->is_active ? 'bg-green-500' : 'bg-red-500' }}"
                                    title="{{ $product->is_active ? 'Active' : 'Inactive' }}"></span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-1 text-xs">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-flex items-center justify-center dark:text-blue-400 dark:hover:bg-blue-900/20"
                                        title="Edit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition dark:text-red-400 dark:hover:bg-red-900/20"
                                            title="Delete">
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
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            {{ $products->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const selectedCount = document.getElementById('selected-count');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkDeleteVisibility() {
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteBtn.disabled = false;
                bulkDeleteBtn.style.backgroundColor = '#dc2626'; // red-600
                bulkDeleteBtn.style.color = '#ffffff';
                bulkDeleteBtn.style.borderColor = '#dc2626';
                bulkDeleteBtn.classList.remove('bg-gray-100', 'text-gray-400', 'border-gray-200', 'cursor-not-allowed');
                bulkDeleteBtn.classList.add('shadow-lg', 'shadow-red-500/20');
                selectedCount.textContent = checkedCount;
                selectedCount.classList.remove('hidden');
            } else {
                bulkDeleteBtn.disabled = true;
                bulkDeleteBtn.style.backgroundColor = '';
                bulkDeleteBtn.style.color = '';
                bulkDeleteBtn.style.borderColor = '';
                bulkDeleteBtn.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200', 'cursor-not-allowed');
                bulkDeleteBtn.classList.remove('shadow-lg', 'shadow-red-500/20');
                selectedCount.textContent = '0';
                selectedCount.classList.add('hidden');
            }
        }

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkDeleteVisibility();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteVisibility);
        });

        function confirmBulkDelete() {
            if (confirm('Are you sure you want to delete ' + document.querySelectorAll('.product-checkbox:checked').length + ' selected products? This action cannot be undone.')) {
                // Append selected IDs to the hidden form and submit
                const checkedIds = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);

                // Clear existing hidden inputs in form
                bulkDeleteForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

                // Add new hidden inputs
                checkedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    bulkDeleteForm.appendChild(input);
                });

                bulkDeleteForm.submit();
            }
        }
    </script>
@endpush