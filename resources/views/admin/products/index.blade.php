@extends('layouts.admin')
@section('title', 'Products Management')
@section('content')

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 p-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search -->
                <div class="relative group flex-1 min-w-[250px]">
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4.5 h-4.5 text-gray-400 group-focus-within:text-orange-500 transition-colors" stroke-width="2.5"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, SKU, brand..."
                        class="w-full border border-gray-200 rounded-xl pl-4 pr-11 py-2.5 text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:outline-none transition-all bg-gray-50/50 focus:bg-white">
                </div>

                <!-- Category -->
                <div class="min-w-[160px]">
                    <select name="category_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:outline-none transition-all bg-gray-50/50">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="min-w-[130px]">
                    <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:outline-none transition-all bg-gray-50/50">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Stock Status -->
                <div class="min-w-[130px]">
                    <select name="stock_status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:outline-none transition-all bg-gray-50/50">
                        <option value="">All Stock</option>
                        <option value="instock" {{ request('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                        <option value="lowstock" {{ request('stock_status') == 'lowstock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="outofstock" {{ request('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <!-- Type Flags -->
                <div class="min-w-[130px]">
                    <select name="type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:outline-none transition-all bg-gray-50/50">
                        <option value="">All Types</option>
                        <option value="new" {{ request('type') == 'new' ? 'selected' : '' }}>New Arrival</option>
                        <option value="featured" {{ request('type') == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="top_sell" {{ request('type') == 'top_sell' ? 'selected' : '' }}>Top Sell</option>
                    </select>
                </div>

                <!-- Sorting -->
                <div class="min-w-[150px]">
                    <select name="sort" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:outline-none transition-all bg-gray-50/50">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="stock_low" {{ request('sort') == 'stock_low' ? 'selected' : '' }}>Stock: Low to High</option>
                        <option value="stock_high" {{ request('sort') == 'stock_high' ? 'selected' : '' }}>Stock: High to Low</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg transition-all font-bold text-sm flex items-center gap-2 shadow-md shadow-orange-500/20">
                        <i data-lucide="filter" class="w-4 h-4"></i> Filter
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-5 py-2.5 rounded-lg transition-all font-bold text-sm flex items-center gap-2">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="flex justify-between items-center mb-4 mx-2">
        <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
            <i data-lucide="package" class="w-5 h-5 text-orange-500"></i>
            Product List 
            <span class="text-[11px] font-bold bg-orange-50 text-orange-600 px-2 py-0.5 rounded-full ml-1">
                {{ $products->total() }} Products
            </span>
        </h3>
        <div class="flex items-center gap-2">
            <button type="button" id="bulk-delete-btn" onclick="confirmBulkDelete()" disabled
                class="bg-gray-100 text-gray-400 px-5 py-2.5 rounded-lg transition-all border border-gray-200 cursor-not-allowed flex items-center gap-2 font-bold text-sm relative">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Selected
                <span id="selected-count" class="hidden absolute -top-2 -right-2 bg-red-600 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-lg">0</span>
            </button>
            <a href="{{ route('admin.products.create') }}"
                class="bg-gradient-to-r from-[#FF6A00] to-[#FF8C00] hover:from-[#FF7A1A] hover:to-[#FFA500] text-white px-5 py-2.5 rounded-lg transition-all shadow-lg shadow-orange-500/30 flex items-center gap-2 font-bold text-sm whitespace-nowrap">
                <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i> Add Product
            </a>
        </div>
    </div>

    <form id="bulk-delete-form" action="{{ route('admin.products.bulk-delete') }}" method="POST" class="hidden">
        @csrf
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{
        toggleStatus(id, field, el) {
            const originalBg = el.className;
            // Visual feedback - opacity during request
            el.style.opacity = '0.5';
            
            fetch(`${window.location.origin}/admin/products/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ field: field })
            })
            .then(res => {
                if (!res.ok) throw new Error('Server returned ' + res.status);
                return res.json();
            })
            .then(data => {
                el.style.opacity = '1';
                if (data.success) {
                    if (data.value) {
                        el.classList.remove('bg-gray-200');
                        el.classList.add('bg-green-500');
                        el.querySelector('.toggle-dot').style.transform = 'translateX(100%)';
                    } else {
                        el.classList.remove('bg-green-500');
                        el.classList.add('bg-gray-200');
                        el.querySelector('.toggle-dot').style.transform = 'translateX(0)';
                    }
                } else {
                    console.error('Toggle failed:', data.message);
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                el.style.opacity = '1';
                console.error('Fetch error:', err);
                alert('Connection error. Please try again.');
            });
        }
    }">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 min-w-[1000px]">
                <thead class="bg-gray-50 text-[11px] uppercase text-gray-500 font-black tracking-wider">
                    <tr>
                        <th class="px-4 py-4 w-10 text-center">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-orange-500 focus:ring-orange-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-4">Product</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Price</th>
                        <th class="px-4 py-4 text-center">Stock</th>
                        <th class="px-4 py-4 text-center">New</th>
                        <th class="px-4 py-4 text-center">Featured</th>
                        <th class="px-4 py-4 text-center">Top Sell</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded border-gray-300 text-orange-500 focus:ring-orange-500 cursor-pointer">
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->thumbnail_url }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100 border border-gray-100">
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 truncate max-w-[200px]">{{ $product->name }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $product->sku }} | {{ $product->brand }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider" style="background: {{ $product->category->color }}15; color: {{ $product->category->color }}">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-900">৳{{ number_format($product->effective_price) }}</div>
                                @if($product->effective_price < $product->price)
                                    <div class="text-[11px] text-gray-400 line-through">৳{{ number_format($product->price) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($product->stock > 10)
                                    <span class="text-green-600 font-black text-[12px]">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="text-orange-500 font-black text-[12px]">{{ $product->stock }}</span>
                                @else
                                    <span class="text-red-500 font-black text-[12px] uppercase">Out</span>
                                @endif
                            </td>
                            
                            <!-- Toggle Columns -->
                            @foreach(['is_new', 'is_featured', 'is_top_sell', 'is_active'] as $field)
                            <td class="px-4 py-4 text-center">
                                <button @click="toggleStatus('{{ $product->id }}', '{{ $field }}', $el)" 
                                    class="w-8 h-4 rounded-full relative transition-colors duration-200 focus:outline-none {{ $product->$field ? 'bg-green-500' : 'bg-gray-200' }}">
                                    <div class="toggle-dot absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform duration-200" 
                                        style="transform: {{ $product->$field ? 'translateX(100%)' : 'translateX(0)' }}"></div>
                                </button>
                            </td>
                            @endforeach

                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
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
        <div class="p-4 border-t border-gray-100">
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
            bulkDeleteBtn.classList.remove('bg-gray-100', 'text-gray-400', 'border-gray-200', 'cursor-not-allowed');
            bulkDeleteBtn.classList.add('bg-red-600', 'text-white', 'border-red-600', 'shadow-lg', 'shadow-red-500/20');
            selectedCount.textContent = checkedCount;
            selectedCount.classList.remove('hidden');
        } else {
            bulkDeleteBtn.disabled = true;
            bulkDeleteBtn.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200', 'cursor-not-allowed');
            bulkDeleteBtn.classList.remove('bg-red-600', 'text-white', 'border-red-600', 'shadow-lg', 'shadow-red-500/20');
            selectedCount.textContent = '0';
            selectedCount.classList.add('hidden');
        }
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkDeleteVisibility();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkDeleteVisibility));

    function confirmBulkDelete() {
        if (confirm('Delete ' + document.querySelectorAll('.product-checkbox:checked').length + ' products?')) {
            const checkedIds = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
            bulkDeleteForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
            checkedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                bulkDeleteForm.appendChild(input);
            });
            bulkDeleteForm.submit();
        }
    }
</script>
@endpush