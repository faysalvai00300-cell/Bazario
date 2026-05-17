@extends('layouts.admin')
@section('title', 'Add Category')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
        Add New Category 
        @if(request('target_page'))
            <span class="text-sm px-3 py-1 bg-cyan-100 text-cyan-700 rounded-none dark:bg-cyan-900/30 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800">
                Category Page {{ request('target_page') }}
            </span>
        @endif
    </h2>
    <a href="{{ route('admin.categories.index', request()->has('target_page') ? ['target_page' => request('target_page')] : []) }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 flex items-center gap-1 group">
        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i> Back to List
    </a>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="bg-white rounded-none border border-gray-300 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Side: Text Fields -->
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Category Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter category name"
                    class="w-full border border-gray-300 rounded-none px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Meta Title"
                        class="w-full border border-gray-300 rounded-none px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="Keywords"
                        class="w-full border border-gray-300 rounded-none px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Meta Description</label>
                <textarea name="meta_description" rows="2" placeholder="Brief description for SEO..."
                    class="w-full border border-gray-300 rounded-none px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">{{ old('meta_description') }}</textarea>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Linked Category (Show products from this category too)</label>
                <select name="linked_category_id" class="w-full border border-gray-300 rounded-none px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="">None (Standalone)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('linked_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('linked_category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                   <i data-lucide="layout" class="w-4 h-4"></i> Homepage Position
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">TARGET PAGE</label>
                        @if(request()->has('target_page'))
                            <input type="hidden" name="target_page" id="targetPage" value="{{ request('target_page') }}">
                            <div class="w-full border border-cyan-200 bg-cyan-50/50 rounded-none px-4 py-2 text-sm font-bold text-cyan-700 flex items-center justify-between">
                                <span>Page {{ request('target_page') }}</span>
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                            </div>
                        @else
                            <select name="target_page" id="targetPage" class="w-full border border-gray-300 rounded-none px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                <option value="">None (Regular)</option>
                                <option value="1">Page 1 (New Arrival Grid)</option>
                                <option value="2">Page 2 (Showcase Style 1)</option>
                                <option value="3">Page 3 (Showcase Style 2)</option>
                                <option value="4">Page 4 (3-Column Grid)</option>
                                <option value="5">Page 5 (Highlight Banners)</option>
                            </select>
                        @endif
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">TARGET BOX</label>
                        <select name="target_box" id="targetBox" class="w-full border border-gray-300 rounded-none px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            <option value="">Select Position</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">TARGET GROUP (Gender)</label>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @php 
                                $currentGenders = (array) old('target_gender', []);
                            @endphp
                            @foreach(['Men', 'Women', 'Kids', 'Sports'] as $gender)
                                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-none cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="target_gender[]" value="{{ $gender }}" 
                                        {{ in_array($gender, $currentGenders) ? 'checked' : '' }}
                                        class="rounded-none text-orange-500 focus:ring-orange-500 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-700">{{ $gender }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Description and Media -->
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Description</label>
                <textarea name="description" rows="5" placeholder="Detailed category description..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Category Image</label>
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                    <label for="cat-image" class="cursor-pointer group flex flex-col items-center justify-center gap-2">
                        <div class="w-full h-32 relative rounded-xl overflow-hidden bg-white dark:bg-gray-800 flex items-center justify-center border border-gray-200 dark:border-gray-700">
                            <img id="image-preview" src="" alt="" class="hidden absolute inset-0 w-full h-full object-cover">
                            <div id="image-placeholder" class="text-center">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-300 group-hover:text-orange-500 transition-colors mx-auto mb-2"></i>
                                <span class="text-xs text-gray-400">Click to upload (JPG, PNG, WebP)</span>
                                <p class="text-[10px] text-gray-400 mt-1">Max size: 20MB</p>
                            </div>
                        </div>
                        <input type="file" name="image" id="cat-image" accept="image/*" class="hidden">
                    </label>
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="{{ (request('target_page') != 2 && request('target_page') != 3 && request('target_page') != 5) ? 'hidden' : '' }}" id="viewAllImageContainer">
                <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">View All Card Image (Page 2, 3 & 5 Only)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                    <label for="view-all-image" class="cursor-pointer group flex flex-col items-center justify-center gap-2">
                        <div class="w-full h-32 relative rounded-xl overflow-hidden bg-white dark:bg-gray-800 flex items-center justify-center border border-gray-200 dark:border-gray-700">
                            <img id="view-all-preview" src="" alt="" class="hidden absolute inset-0 w-full h-full object-cover">
                            <div id="view-all-placeholder" class="text-center">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-300 group-hover:text-orange-500 transition-colors mx-auto mb-2"></i>
                                <span class="text-xs text-gray-400">Click to upload (JPG, PNG, WebP)</span>
                                <p class="text-[10px] text-gray-400 mt-1">Max size: 20MB</p>
                            </div>
                        </div>
                        <input type="file" name="view_all_image" id="view-all-image" accept="image/*" class="hidden">
                    </label>
                </div>
                @error('view_all_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block dark:text-gray-300">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer p-2 px-4 bg-gray-50 rounded-xl border border-gray-300 dark:bg-gray-900/50 dark:border-gray-700 w-full h-[40px]">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-orange-500 focus:ring-orange-500 w-5 h-5">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Active Status</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Linked Products Selection -->
    <div x-data="linkedProductsHandler()" class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="link" class="w-4 h-4"></i> Linked Products (Many-to-Many)
        </h3>

        <!-- Search Input -->
        <div class="relative mb-6 max-w-2xl">
            <label class="text-xs font-medium text-gray-500 mb-1.5 block">SEARCH PRODUCTS TO LINK</label>
            <div class="flex items-center border border-gray-300 dark:border-gray-700 rounded-none bg-white dark:bg-gray-900 px-4 py-2.5 focus-within:ring-1 focus-within:ring-orange-400">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 mr-2"></i>
                <input type="text" x-model="search" @input.debounce.300ms="filterProducts" placeholder="Search products by name or SKU..." class="w-full text-sm bg-transparent border-none focus:ring-0 p-0 dark:text-white">
            </div>

            <!-- Search Results Dropdown -->
            <div x-show="search.length > 1 && filteredResults.length > 0" 
                 class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-2xl max-h-80 overflow-y-auto"
                 @click.away="filteredResults = []">
                <template x-for="product in filteredResults" :key="product.id">
                    <div @click="selectProduct(product)" class="flex items-center gap-3 p-3 hover:bg-orange-50 dark:hover:bg-orange-900/20 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0 group">
                        <img :src="product.thumbnail" class="w-12 h-12 object-cover border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-600" x-text="product.name"></p>
                            <p class="text-xs text-gray-500" x-text="'SKU: ' + product.sku"></p>
                        </div>
                        <i data-lucide="plus-circle" class="w-5 h-5 text-gray-300 group-hover:text-orange-500"></i>
                    </div>
                </template>
            </div>
            <div x-show="search.length > 1 && filteredResults.length === 0" class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 text-center text-gray-500 text-sm">
                No products found matching "<span x-text="search" class="font-bold"></span>"
            </div>
        </div>

        <!-- Selected Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
            <template x-for="product in selectedProducts" :key="product.id">
                <div class="relative group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-2 shadow-sm hover:border-orange-300 transition-colors">
                    <div class="aspect-square mb-2 relative overflow-hidden bg-gray-50">
                        <img :src="product.thumbnail" @click="imagePreview = product.thumbnail" class="w-full h-full object-cover cursor-zoom-in">
                    </div>
                    <p class="text-[10px] font-bold text-gray-900 dark:text-white truncate" x-text="product.name"></p>
                    <p class="text-[10px] text-gray-500" x-text="product.sku"></p>
                    
                    <button type="button" @click="removeProduct(product.id)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                        <i data-lucide="x" class="w-3 h-3"></i>
                    </button>
                    
                    <input type="hidden" name="linked_product_ids[]" :value="product.id">
                </div>
            </template>
            <div x-show="selectedProducts.length === 0" class="col-span-full py-8 text-center border-2 border-dashed border-gray-200 dark:border-gray-700 text-gray-400 text-sm">
                No products linked yet. Search above to add products.
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div x-show="imagePreview" 
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="imagePreview = null" 
             x-cloak>
            <button type="button" @click="imagePreview = null" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors">
                <i data-lucide="x" class="w-10 h-10"></i>
            </button>
            <img :src="imagePreview" class="max-w-full max-h-full object-contain shadow-2xl ring-1 ring-white/10" @click.away="imagePreview = null">
        </div>
    </div>

    <!-- Bottom Action Button -->
    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
        <button type="submit" class="bg-[#FF6A00] hover:bg-[#FF7A1A] text-white px-12 py-3 rounded-none font-bold transition-all shadow-lg shadow-orange-500/20 flex items-center gap-2 transform active:scale-95 text-sm">
            <i data-lucide="save" class="w-5 h-5"></i> SAVE CATEGORY
        </button>
    </div>
</div>
</form>

@push('scripts')
<script>
    const colorPicker = document.getElementById('colorPicker');
    const colorInput = document.getElementById('colorInput');
    if (colorPicker && colorInput) {
        colorPicker.addEventListener('input', function() {
            colorInput.value = this.value;
        });
        colorInput.addEventListener('input', function() {
            colorPicker.value = this.value;
        });
    }
    document.getElementById('cat-image').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        // Check file size (20MB limit)
        if (file.size > 20 * 1024 * 1024) {
            alert('Error: Image size is too large (' + (file.size / (1024 * 1024)).toFixed(2) + 'MB). Maximum allow is 20MB. Please resize your image.');
            this.value = ''; // Clear selection
            return;
        }

        var reader = new FileReader();
        reader.onload = function(ev) {
            var preview = document.getElementById('image-preview');
            var placeholder = document.getElementById('image-placeholder');
            preview.src = ev.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('view-all-image')?.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        if (file.size > 20 * 1024 * 1024) {
            alert('Error: Image size is too large. Max size: 20MB.');
            this.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function(ev) {
            var preview = document.getElementById('view-all-preview');
            var placeholder = document.getElementById('view-all-placeholder');
            preview.src = ev.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    const boxCounts = {
        1: 18,
        2: 2,
        3: 3,
        4: 3,
        5: 5
    };

    const targetPageSelect = document.getElementById('targetPage');
    const targetBoxSelect = document.getElementById('targetBox');

    const occupiedBoxes = @json($occupiedBoxes);

    function updateBoxOptions() {
        const page = targetPageSelect ? targetPageSelect.value : '';
        const currentBox = "{{ old('target_box', isset($category) ? $category->target_box : '') }}";
        const currentCategoryId = "{{ isset($category) ? $category->id : '' }}";
        
        targetBoxSelect.innerHTML = '<option value="">Select Position</option>';

        // Show/Hide View All Image Container
        const container = document.getElementById('viewAllImageContainer');
        if (container) {
            if (page == '2' || page == '3' || page == '5') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
        
        if (page && boxCounts[page]) {
            const pageOccupied = occupiedBoxes[page] || {};
            for (let i = 1; i <= boxCounts[page]; i++) {
                const option = document.createElement('option');
                option.value = i;
                
                let label = `Position ${i}`;
                if (pageOccupied[i]) {
                    label += ` (Occupied: ${pageOccupied[i]})`;
                    option.style.color = '#94a3b8';
                }
                
                option.textContent = label;
                if (currentBox == i) option.selected = true;
                targetBoxSelect.appendChild(option);
            }
        }
    }

    if (targetPageSelect) {
        targetPageSelect.addEventListener('change', updateBoxOptions);
    }
    updateBoxOptions();

    function linkedProductsHandler() {
        const productsEl = document.getElementById('all-products-json');
        const allProducts = productsEl ? JSON.parse(productsEl.textContent) : [];
        
        return {
            search: '',
            allProducts: allProducts,
            selectedProducts: [],
            filteredResults: [],
            imagePreview: null,
            
            filterProducts() {
                if (this.search.length < 2) {
                    this.filteredResults = [];
                    return;
                }
                const s = this.search.toLowerCase();
                this.filteredResults = this.allProducts.filter(p => 
                    (p.name.toLowerCase().includes(s) || p.sku.toLowerCase().includes(s)) &&
                    !this.selectedProducts.find(sp => sp.id === p.id)
                ).slice(0, 15);
            },
            
            selectProduct(product) {
                this.selectedProducts.push(product);
                this.search = '';
                this.filteredResults = [];
                setTimeout(() => { if(typeof lucide !== 'undefined') lucide.createIcons(); }, 10);
            },
            
            removeProduct(id) {
                this.selectedProducts = this.selectedProducts.filter(p => p.id !== id);
            }
        }
    }
</script>
<script type="application/json" id="all-products-json">
    @json($allProducts)
</script>
@endpush
@endsection
