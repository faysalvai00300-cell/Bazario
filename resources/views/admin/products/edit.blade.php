@extends('layouts.admin')
@section('title', 'Edit Product: ' . $product->name)

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<style>
    .sortable-ghost {
        opacity: 0.4;
        background-color: #f3f4f6 !important;
    }
    .sortable-drag {
        opacity: 0.9;
        transform: scale(1.05);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Product</h2>
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 flex items-center gap-1 group">
        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i> Back to List
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6 w-full mx-auto dark:bg-gray-800 dark:border-gray-700">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6"
        x-data="{ 
            targetPageFilter: '{{ $product->category?->target_page ?? '' }}',
            selectedCategoryId: '{{ $product->category_id }}',
            selectedCategoryPage: '{{ $product->category?->target_page ?? '' }}',
            featuredCounts: {{ json_encode($featuredCounts) }},
            limits: { '1': 14, '2': 14, '3': 14, '4': 20, '5': 20 },
            isOriginalFeatured: {{ $product->is_featured ? 'true' : 'false' }},
            originalCategoryId: '{{ $product->category_id }}',
            get currentCount() { return this.featuredCounts[this.selectedCategoryId] || 0 },
            get currentLimit() { return this.limits[this.selectedCategoryPage] || 20 },
            get isLimitReached() { 
                let countToCompare = this.currentCount;
                if (this.isOriginalFeatured && this.selectedCategoryId == this.originalCategoryId) {
                    countToCompare = countToCompare - 1;
                }
                return countToCompare >= this.currentLimit;
            },
            get isPageSelected() { return this.selectedCategoryId !== '' },
            columns: {{ !empty($product->size_chart['columns']) ? json_encode($product->size_chart['columns']) : "['Size', 'Chest (round)', 'Length']" }},
            rows: {{ !empty($product->size_chart['rows']) ? json_encode($product->size_chart['rows']) : "[['M', '39', '27.5']]" }},
            addColumn() { this.columns.push('New Column'); this.rows.forEach(row => row.push('')); },
            removeColumn(index) { this.columns.splice(index, 1); this.rows.forEach(row => row.splice(index, 1)); },
            addRow() { this.rows.push(new Array(this.columns.length).fill('')); },
            removeRow(index) { this.rows.splice(index, 1); },
            colorMethod: 'manual',
            is_mega_deal: {{ $product->is_mega_deal ? 'true' : 'false' }}
        }">
        @csrf @method('PUT')
        <input type="hidden" name="image_order" id="image_order">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-5">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Category Page</label>
                        <select id="target_page_filter" x-model="targetPageFilter" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                            <option value="">All Pages</option>
                            <option value="1" {{ $product->category->target_page == 1 ? 'selected' : '' }}>Category Page 1</option>
                            <option value="2" {{ $product->category->target_page == 2 ? 'selected' : '' }}>Category Page 2</option>
                            <option value="3" {{ $product->category->target_page == 3 ? 'selected' : '' }}>Category Page 3</option>
                            <option value="4" {{ $product->category->target_page == 4 ? 'selected' : '' }}>Category Page 4</option>
                            <option value="5" {{ $product->category->target_page == 5 ? 'selected' : '' }}>Category Page 5</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Category *</label>
                        <select name="category_id" id="category_select" required x-model="selectedCategoryId"
                            @change="selectedCategoryPage = $event.target.options[$event.target.selectedIndex].getAttribute('data-page')"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-page="{{ $cat->target_page }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Buying Price *</label>
                        <input type="number" name="buying_price" value="{{ old('buying_price', $product->buying_price) }}" required min="0" step="0.01"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Regular Price *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required step="0.01" placeholder="0.00"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Sale Price</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="0.01"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Product Code (SKU)</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="e.g. SLB-001"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Brand Name</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Sizes (Comma separated)</label>
                        <input type="text" name="sizes" value="{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : '') }}" placeholder="e.g. S, M, L, XL"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        
                        <div class="mt-3 p-3 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/20">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-600 w-5 h-5 dark:bg-gray-900 dark:border-gray-700">
                                <div>
                                    <span class="text-sm font-medium text-gray-900 block dark:text-white">Active Status</span>
                                    <span class="text-[10px] text-gray-500 block dark:text-gray-400">Visible on website</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Product Colors & Swatches</label>
                        
                        <!-- Color List Area -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800 p-4">
                            <div id="selected_colors_list" class="space-y-3">
                                <!-- Colors will be added here via JS -->
                                <p id="no_colors_msg" class="text-xs text-gray-400 italic text-center py-2">No colors added yet.</p>
                            </div>
                        </div>

                        <!-- Add Methods Tabs -->
                        <div x-data="{ addMethod: 'manual' }" class="space-y-4">
                            <div class="flex gap-2 p-1 bg-gray-100 dark:bg-gray-900 rounded-xl w-fit">
                                <button type="button" @click="addMethod = 'manual'" :class="addMethod === 'manual' ? 'bg-white dark:bg-800 shadow-sm text-orange-600' : 'text-gray-500'" class="px-3 py-1.5 text-[10px] font-bold uppercase rounded-lg transition">Manual / RGB</button>
                                <button type="button" @click="addMethod = 'picker'" :class="addMethod === 'picker' ? 'bg-white dark:bg-800 shadow-sm text-orange-600' : 'text-gray-500'" class="px-3 py-1.5 text-[10px] font-bold uppercase rounded-lg transition">Picker</button>
                                <button type="button" @click="addMethod = 'detect'" :class="addMethod === 'detect' ? 'bg-white dark:bg-800 shadow-sm text-orange-600' : 'text-gray-500'" class="px-3 py-1.5 text-[10px] font-bold uppercase rounded-lg transition">Extract</button>
                            </div>

                            <!-- Manual / RGB Input -->
                            <div x-show="addMethod === 'manual'" class="flex gap-2">
                                <input type="text" id="color_manual_value" placeholder="e.g. Red, #FF0000, rgb(255,0,0)" class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                <button type="button" onclick="addColorFromManual()" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-700 transition">Add</button>
                            </div>

                            <!-- Picker Input -->
                            <div x-show="addMethod === 'picker'" class="flex gap-2 items-center" x-cloak>
                                <div class="flex-1 flex gap-2 items-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-1.5">
                                    <input type="color" id="color_picker_value" class="w-8 h-8 rounded-lg cursor-pointer border-none bg-transparent">
                                    <span class="text-xs text-gray-500 font-medium" id="picker_value_label">#000000</span>
                                </div>
                                <button type="button" onclick="addColorFromPicker()" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-700 transition">Add</button>
                            </div>

                            <!-- Extract Input -->
                            <div x-show="addMethod === 'detect'" class="space-y-3" x-cloak>
                                <div class="relative group">
                                    <input type="file" id="color_detect_file" accept="image/*" class="hidden">
                                    <label for="color_detect_file" class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition dark:border-gray-700 dark:bg-gray-900">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">Click to Extract Colors from Image</p>
                                        <img id="color_detect_preview" class="absolute inset-0 w-full h-full object-contain hidden rounded-2xl p-1 bg-white dark:bg-gray-800">
                                    </label>
                                </div>
                                <p id="detect_hint" class="text-[9px] text-gray-400 italic hidden text-center">Click on image to extract color name</p>
                            </div>
                        </div>

                        <!-- Hidden Master Inputs -->
                        <div id="hidden_color_inputs"></div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Description</label>
                    <div class="ck-editor-container">
                        <textarea name="description" id="editor" rows="5"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Primary Image URL (Optional Fallback)</label>
                    <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url') }}" placeholder="https://example.com/image.jpg"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none mb-4 dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                </div>


                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Primary Image (Upload)</label>
                            <input type="file" name="thumbnail" accept="image/*" id="thumbnail_input"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:file:bg-orange-900/20 dark:file:text-orange-400">
                            <p class="text-xs text-gray-400 mt-1 pb-2 dark:text-gray-500">Leave empty to keep current image. Recommended: 800x800px max 2MB.</p>
                            
                            <!-- Current / New Image Preview -->
                            <div id="thumbnail_preview_container" class="mt-3 relative w-32 h-32 {{ $product->thumbnail ? '' : 'hidden' }}">
                                <div class="absolute top-1 left-1 bg-black/60 text-white w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black z-10 border border-white/20">1</div>
                                <img id="thumbnail_preview" src="{{ $product->thumbnail_url }}" alt="Thumbnail Preview" class="h-32 w-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                                @if($product->thumbnail)
                                <a href="{{ $product->thumbnail_url }}" download class="absolute top-1 right-1 bg-blue-500 text-white rounded-full p-1.5 shadow-md hover:bg-blue-600 transition z-20" title="Download Image">
                                    <i data-lucide="download" class="w-3 h-3"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Upload Gallery Images (Multiple)</label>
                            <input type="file" id="gallery_input" accept="image/*" multiple="multiple"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:file:bg-gray-800 dark:file:text-gray-300">
                            <p class="text-xs text-gray-400 mt-1 pb-2 dark:text-gray-500">Select images to add to the gallery. You can reorder images by hovering over them and using the arrow buttons.</p>
                            
                            <!-- Hidden input to actually submit the files -->
                            <input type="file" name="images[]" id="gallery_submit_input" multiple="multiple" class="hidden">

                            <!-- Gallery Preview Area -->
                            <div id="gallery_preview_container" class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3 hidden">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-5 lg:col-span-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <label class="flex gap-3 p-4 border rounded-xl transition items-start dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50"
                            :class="(!isPageSelected || isLimitReached) ? 'bg-gray-50 border-gray-200 cursor-not-allowed' : 'border-gray-200 cursor-pointer'">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} 
                                :disabled="!isPageSelected || isLimitReached"
                                class="rounded text-orange-500 focus:ring-orange-500 w-5 h-5 mt-0.5 dark:bg-gray-900 dark:border-gray-700 disabled:opacity-40">
                            <div>
                                <span class="text-sm font-medium text-gray-900 block dark:text-white" :class="(!isPageSelected || isLimitReached) ? 'opacity-70' : ''">Show on Home Page</span>
                                <template x-if="!isPageSelected">
                                    <span class="text-[10px] text-orange-500 font-medium italic mt-1 block">Please select Category Page first</span>
                                </template>
                                <template x-if="isPageSelected">
                                    <div class="mt-2 flex items-center flex-wrap gap-2 text-[11px] font-black">
                                        <span class="text-red-700">Limit: <span x-text="currentLimit"></span>,</span>
                                        <span style="background-color: #1d4ed8 !important; color: #ffffff !important; display: inline-flex !important;" class="px-2.5 py-1 rounded shadow-sm items-center">
                                            Running: <span x-text="currentCount" class="ml-1"></span>
                                            <span x-show="isLimitReached" class="ml-1 border-l border-white/30 pl-1">[Full]</span>
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </label>

                        <label class="flex gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition items-start dark:border-gray-700 dark:hover:bg-gray-700/50">
                            <input type="hidden" name="is_top_sell" value="0">
                            <input type="checkbox" name="is_top_sell" value="1" {{ old('is_top_sell', $product->is_top_sell) ? 'checked' : '' }} class="rounded text-orange-500 focus:ring-orange-500 w-5 h-5 mt-0.5 dark:bg-gray-900 dark:border-gray-700">
                            <div>
                                <span class="text-sm font-medium text-gray-900 block dark:text-white">Top Selling</span>
                                <span class="text-xs text-gray-500 block dark:text-gray-400">Show in Top Selling section</span>
                            </div>
                        </label>
                        <label class="flex gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition items-start dark:border-gray-700 dark:hover:bg-gray-700/50">
                            <input type="hidden" name="is_mega_deal" value="0">
                            <input type="checkbox" name="is_mega_deal" value="1" x-model="is_mega_deal" class="rounded text-orange-500 focus:ring-orange-500 w-5 h-5 mt-0.5 dark:bg-gray-900 dark:border-gray-700">
                            <div>
                                <span class="text-sm font-medium text-gray-900 block dark:text-white">Mega Deal</span>
                                <span class="text-xs text-gray-500 block dark:text-gray-400">Apply special deal effects</span>
                                <div class="mt-2" x-show="is_mega_deal" x-cloak>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Animation Effect</label>
                                    <select name="mega_deal_effect" class="w-full border border-gray-200 rounded-lg px-2 py-1 text-[11px] focus:ring-1 focus:ring-orange-400 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                        <option value="none" {{ old('mega_deal_effect', $product->mega_deal_effect) == 'none' ? 'selected' : '' }}>No Animation</option>
                                        <option value="shine" {{ old('mega_deal_effect', $product->mega_deal_effect) == 'shine' ? 'selected' : '' }}>Basic Shine</option>
                                        <option value="border" {{ old('mega_deal_effect', $product->mega_deal_effect) == 'border' ? 'selected' : '' }}>Rotating Border</option>
                                        <option value="confetti" {{ old('mega_deal_effect', $product->mega_deal_effect) == 'confetti' ? 'selected' : '' }}>Celebration (Confetti)</option>
                                        <option value="shine_border" {{ old('mega_deal_effect', $product->mega_deal_effect) == 'shine_border' ? 'selected' : '' }}>Shine + Border</option>
                                        <option value="all" {{ old('mega_deal_effect', $product->mega_deal_effect ?? 'all') == 'all' ? 'selected' : '' }}>Full Combo (All)</option>
                                    </select>
                                </div>
                            </div>
                        </label>
                        <label class="flex gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition items-start dark:border-gray-700 dark:hover:bg-gray-700/50">
                            <input type="hidden" name="is_new" value="0">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="rounded text-orange-500 focus:ring-orange-500 w-5 h-5 mt-0.5 dark:bg-gray-900 dark:border-gray-700">
                            <div>
                                <span class="text-sm font-medium text-gray-900 block dark:text-white">New Arrival</span>
                                <span class="text-xs text-gray-500 block dark:text-gray-400">Mark as new</span>
                            </div>
                        </label>
                        <label class="flex gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition items-start dark:border-gray-700 dark:hover:bg-gray-700/50">
                            <input type="hidden" name="free_shipping" value="0">
                            <input type="checkbox" name="free_shipping" value="1" {{ old('free_shipping', $product->free_shipping) ? 'checked' : '' }} class="rounded text-green-500 focus:ring-green-500 w-5 h-5 mt-0.5 dark:bg-gray-900 dark:border-gray-700">
                            <div>
                                <span class="text-sm font-medium text-gray-900 block dark:text-white">Free Shipping</span>
                                <span class="text-xs text-gray-500 block dark:text-gray-400">No delivery charge</span>
                            </div>
                        </label>
                    </div>
                </div>
                <!-- Size Chart Section -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="p-6 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Dynamic Size Chart</h3>
                                    <p class="text-xs text-gray-500 mt-1">Manage rows and columns for the product size table.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer ml-4" x-data="{ active: {{ old('is_size_chart_active', $product->is_size_chart_active ?? true) ? 'true' : 'false' }} }">
                                    <input type="hidden" name="is_size_chart_active" value="0">
                                    <input type="checkbox" name="is_size_chart_active" value="1" class="sr-only peer" x-model="active">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500"></div>
                                    <span class="ml-2 text-xs font-bold transition-colors duration-200" :class="active ? 'text-orange-600' : 'text-gray-400'" x-text="active ? 'Enabled' : 'Disabled'">Enabled</span>
                                </label>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="addColumn()" class="px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-100 transition">
                                    + Add Column
                                </button>
                                <button type="button" @click="addRow()" class="px-3 py-1.5 bg-green-50 text-green-600 text-xs font-bold rounded-lg hover:bg-green-100 transition">
                                    + Add Row
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead>
                                    <tr>
                                        <template x-for="(col, i) in columns" :key="i">
                                            <th class="p-2 border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                                                <div class="flex items-center gap-2">
                                                    <input type="text" x-model="columns[i]" class="bg-transparent border-none p-0 focus:ring-0 font-bold text-xs w-full min-w-[80px]">
                                                    <button type="button" @click="if(columns.length > 1) removeColumn(i)" class="text-red-400 hover:text-red-600 transition-colors" title="Delete Column">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        <th class="w-10 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-center text-[10px] text-gray-400">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, i) in rows" :key="i">
                                        <tr>
                                            <template x-for="(cell, j) in row" :key="j">
                                                <td class="p-2 border border-gray-200 dark:border-gray-700">
                                                    <input type="text" x-model="rows[i][j]" class="bg-transparent border-none p-0 focus:ring-0 text-xs w-full">
                                                </td>
                                            </template>
                                            <td class="p-2 border border-gray-200 dark:border-gray-700 text-center">
                                                <button type="button" @click="if(rows.length > 1) removeRow(i)" class="text-red-400 hover:text-red-600 transition-colors" title="Delete Row">
                                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Hidden input to store JSON -->
                        <input type="hidden" name="size_chart" :value="JSON.stringify({columns: columns, rows: rows})">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3 mt-8 dark:border-gray-700">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 text-center sm:w-auto w-full order-2 sm:order-1">Cancel</a>
                <button type="submit" class="bg-[#FF6A00] hover:bg-[#FF7A1A] text-white px-10 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-orange-500/20 transition transform active:scale-95 sm:w-auto w-full order-1 sm:order-2">Update Product</button>
            </div>
    </form>

    <!-- Existing Gallery Images -->
    @if($product->images->count() > 0)
    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700" x-data="{}">
        <h3 class="text-md font-bold text-gray-800 mb-2 dark:text-white">Existing Gallery Images</h3>
        <p class="text-xs text-gray-400 mb-4">Drag and drop images to reorder them. Click "Update Product" to save the new order.</p>
        
        <div id="sortable-gallery" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4"
             x-init="
                $nextTick(() => {
                    if (typeof Sortable !== 'undefined') {
                        Sortable.create($el, {
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            dragClass: 'sortable-drag',
                            handle: '.drag-handle',
                            forceFallback: true,
                            onEnd: function() {
                                updateImageOrder();
                            }
                        });
                        updateImageOrder();
                    }
                })
             ">
            @foreach($product->images->sortBy('sort_order') as $index => $image)
            <div class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800" data-id="{{ $image->id }}">
                <div class="absolute top-1 left-1 bg-black/60 text-white w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black z-10 border border-white/20 index-badge">{{ $index + 1 }}</div>
                <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-24 object-cover" alt="Gallery Image">
                
                <!-- Drag Handle -->
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-move drag-handle">
                    <i data-lucide="move" class="w-8 h-8 text-white"></i>
                </div>

                <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition z-20 flex flex-col gap-1">
                    <a href="{{ asset('storage/' . $image->image) }}" download class="bg-blue-500 text-white rounded-full p-1.5 shadow-md hover:bg-blue-600 transition flex items-center justify-center" title="Download Image">
                        <i data-lucide="download" class="w-3 h-3"></i>
                    </a>
                    <form action="{{ route('admin.products.images.destroy', $image->id) }}" method="POST" 
                        onsubmit="deleteExistingImage(event, this)">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white rounded-full p-1.5 shadow-md hover:bg-red-600 transition" title="Delete Image">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Thumbnail Preview
    document.getElementById('thumbnail_input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('thumbnail_preview_container');
        const previewImage = document.getElementById('thumbnail_preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            // Keep current image if cancel is pressed
            previewImage.src = '{{ $product->thumbnail_url }}';
            if (!'{{ $product->thumbnail_url }}') {
                previewContainer.classList.add('hidden');
            }
        }
    });

    // Gallery Multiple Appending & Preview
    let selectedGalleryFiles = [];
    const galleryInput = document.getElementById('gallery_input');
    const gallerySubmitInput = document.getElementById('gallery_submit_input');
    const galleryPreviewContainer = document.getElementById('gallery_preview_container');

    galleryInput.addEventListener('change', function(event) {
        const files = Array.from(event.target.files);
        if (files.length === 0) return;

        // Add new files to our running array
        selectedGalleryFiles = selectedGalleryFiles.concat(files);
        
        // Render Previews
        renderGalleryPreviews();
        
        // Sync with actual hidden submit input via DataTransfer
        updateSubmitInput();
        
        // Reset the visible input so user can select the *same* file again if they deleted it
        galleryInput.value = '';
    });

    function renderGalleryPreviews() {
        galleryPreviewContainer.innerHTML = ''; // Clear current previews
        
        if (selectedGalleryFiles.length > 0) {
            galleryPreviewContainer.classList.remove('hidden');
        } else {
            galleryPreviewContainer.classList.add('hidden');
        }

        selectedGalleryFiles.forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'relative group rounded-xl overflow-hidden border border-gray-200 aspect-square bg-gray-50';
            
            // Placeholder/Loading state
            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover opacity-0 transition-opacity duration-300';
            
            // Index badge
            const badge = document.createElement('div');
            badge.className = 'absolute top-1 left-1 bg-black/60 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold z-10';
            badge.textContent = index + 1;

            // Controls Overlay
            const controls = document.createElement('div');
            controls.className = 'absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2';

            // Move Left
            if (index > 0) {
                const leftBtn = document.createElement('button');
                leftBtn.type = 'button';
                leftBtn.className = 'bg-white/20 hover:bg-white/40 text-white rounded-full p-1 transition';
                leftBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>';
                leftBtn.onclick = (e) => { e.preventDefault(); moveGalleryImage(index, -1); };
                controls.appendChild(leftBtn);
            }

            // Remove
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'bg-red-500 hover:bg-red-600 text-white rounded-full p-1 transition';
            removeBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
            removeBtn.onclick = (e) => { e.preventDefault(); removeGalleryImage(index); };
            controls.appendChild(removeBtn);

            // Move Right
            if (index < selectedGalleryFiles.length - 1) {
                const rightBtn = document.createElement('button');
                rightBtn.type = 'button';
                rightBtn.className = 'bg-white/20 hover:bg-white/40 text-white rounded-full p-1 transition';
                rightBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                rightBtn.onclick = (e) => { e.preventDefault(); moveGalleryImage(index, 1); };
                controls.appendChild(rightBtn);
            }

            div.appendChild(img);
            div.appendChild(badge);
            div.appendChild(controls);
            galleryPreviewContainer.appendChild(div);

            // Load Image Async
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.classList.remove('opacity-0');
            }
            reader.readAsDataURL(file);
        });
    }

    function moveGalleryImage(index, direction) {
        const newIndex = index + direction;
        if (newIndex < 0 || newIndex >= selectedGalleryFiles.length) return;
        
        // Swap elements
        const temp = selectedGalleryFiles[index];
        selectedGalleryFiles[index] = selectedGalleryFiles[newIndex];
        selectedGalleryFiles[newIndex] = temp;
        
        renderGalleryPreviews();
        updateSubmitInput();
    }

    function removeGalleryImage(index) {
        selectedGalleryFiles.splice(index, 1);
        renderGalleryPreviews();
        updateSubmitInput();
    }

    function updateSubmitInput() {
        const dataTransfer = new DataTransfer();
        selectedGalleryFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        gallerySubmitInput.files = dataTransfer.files;
    }

    // Category Page Filter Logic
    const pageFilter = document.getElementById('target_page_filter');
    const categorySelect = document.getElementById('category_select');
    const originalOptions = Array.from(categorySelect.options);

    function filterCategories() {
        const selectedPage = pageFilter.value;
        const currentSelectedId = categorySelect.value;
        
        // Clear current options
        categorySelect.innerHTML = '';
        
        // Filter and add back relevant options
        originalOptions.forEach(option => {
            if (!selectedPage || option.value === "" || option.getAttribute('data-page') === selectedPage) {
                const newOption = option.cloneNode(true);
                // Keep selected if it was originally selected and still matches the filter
                if (newOption.value === currentSelectedId) {
                    newOption.selected = true;
                }
                categorySelect.appendChild(newOption);
            }
        });
    }

    pageFilter.addEventListener('change', () => {
        filterCategories();
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        if (selectedOption) {
            Alpine.find(form).selectedCategoryPage = selectedOption.getAttribute('data-page') || '';
            Alpine.find(form).selectedCategoryId = categorySelect.value;
        }
    });

    categorySelect.addEventListener('change', (e) => {
        const selectedOption = e.target.options[e.target.selectedIndex];
        if (selectedOption) {
            Alpine.find(form).selectedCategoryPage = selectedOption.getAttribute('data-page') || '';
            Alpine.find(form).selectedCategoryId = e.target.value;
        }
    });
    
    // Color Manager Logic
    let productColors = [];
    const colorListArea = document.getElementById('selected_colors_list');
    const noColorsMsg = document.getElementById('no_colors_msg');
    const hiddenInputsArea = document.getElementById('hidden_color_inputs');
    const colorPickerInput = document.getElementById('color_picker_value');
    const pickerLabel = document.getElementById('picker_value_label');

    // Bootstrap existing colors
    @if(!empty($product->colors))
        @php 
            $swatches = $product->color_swatches ?? []; 
            $indices = $product->color_image_indices ?? [];
        @endphp
        @foreach($product->colors as $index => $c)
            productColors.push({ 
                value: '{{ $c }}', 
                type: 'text', 
                existingSwatch: '{{ isset($swatches[$index]) ? $swatches[$index] : "" }}',
                imageIndex: '{{ isset($indices[$index]) ? $indices[$index] : "" }}'
            });
        @endforeach
    @endif

    colorPickerInput.addEventListener('input', (e) => {
        pickerLabel.textContent = e.target.value.toUpperCase();
    });

    const commonColors = [
        { name: 'Red', rgb: [255, 0, 0] }, { name: 'Blue', rgb: [0, 0, 255] }, { name: 'Green', rgb: [0, 255, 0] },
        { name: 'Yellow', rgb: [255, 255, 0] }, { name: 'Black', rgb: [0, 0, 0] }, { name: 'White', rgb: [255, 255, 255] },
        { name: 'Orange', rgb: [255, 165, 0] }, { name: 'Purple', rgb: [128, 0, 128] }, { name: 'Pink', rgb: [255, 192, 203] },
        { name: 'Brown', rgb: [165, 42, 42] }, { name: 'Navy', rgb: [0, 0, 128] }, { name: 'Teal', rgb: [0, 128, 128] },
        { name: 'Silver', rgb: [192, 192, 192] }, { name: 'Gold', rgb: [255, 215, 0] }, { name: 'Beige', rgb: [245, 245, 220] }
    ];

    function getNearestColorName(r, g, b) {
        let minDistance = Infinity;
        let nearestName = "Unknown";
        commonColors.forEach(c => {
            const distance = Math.sqrt(Math.pow(r - c.rgb[0], 2) + Math.pow(g - c.rgb[1], 2) + Math.pow(b - c.rgb[2], 2));
            if (distance < minDistance) { minDistance = distance; nearestName = c.name; }
        });
        return nearestName;
    }

    function addColor(value, type = 'text') {
        if (!value) return;
        productColors.push({ value, type, swatch: null });
        renderColorList();
    }

    window.addColorFromManual = function() {
        const input = document.getElementById('color_manual_value');
        if (input.value.trim()) {
            addColor(input.value.trim());
            input.value = '';
        }
    };

    window.addColorFromPicker = function() {
        addColor(colorPickerInput.value.toUpperCase(), 'picker');
    };

    function renderColorList() {
        colorListArea.innerHTML = '';
        hiddenInputsArea.innerHTML = '';
        
        if (productColors.length === 0) {
            colorListArea.appendChild(noColorsMsg);
            return;
        }

        productColors.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'flex flex-col sm:flex-row sm:items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700 gap-3';
            
            const isHex = item.value.startsWith('#');
            const isRGB = item.value.startsWith('rgb');
            const assetPath = item.existingSwatch ? '{{ asset("storage") }}/' + item.existingSwatch : '';
            
            div.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-lg border border-gray-100 dark:border-gray-700 shrink-0" style="background-color: ${isHex || isRGB ? item.value : 'transparent'}; display: ${isHex || isRGB ? 'block' : 'none'}"></div>
                    <span class="text-xs font-bold dark:text-gray-300 truncate">${item.value}</span>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto">
                    <!-- Image Index Input -->
                    <div class="flex flex-col items-center">
                        <label class="text-[8px] uppercase text-gray-400 font-black mb-1">Img #</label>
                        <input type="number" name="color_image_indices[${index}]" value="${item.imageIndex || ''}" 
                            min="1" placeholder="#" class="w-12 border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg px-2 py-1 text-[10px] focus:ring-1 focus:ring-orange-400 text-center">
                    </div>
                    
                    <div class="relative group">
                        <input type="file" name="color_swatches[${index}]" id="swatch_file_${index}" class="hidden" accept="image/*" onchange="previewSwatch(${index}, this)">
                        <input type="hidden" name="existing_swatches[${index}]" value="${item.existingSwatch || ''}">
                        <label for="swatch_file_${index}" class="cursor-pointer bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-1.5 rounded-lg hover:bg-gray-100 transition block">
                            <img id="swatch_preview_${index}" src="${assetPath}" class="w-5 h-5 object-cover rounded ${item.existingSwatch ? '' : 'hidden'}">
                            <i data-lucide="image-plus" class="w-3.5 h-3.5 text-gray-400 ${item.existingSwatch ? 'hidden' : ''}" id="swatch_icon_${index}"></i>
                        </label>
                    </div>
                    <button type="button" onclick="removeColor(${index})" class="text-red-400 hover:text-red-600 p-1"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            `;
            colorListArea.appendChild(div);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'colors[]';
            hidden.value = item.value;
            hiddenInputsArea.appendChild(hidden);
        });
        
        if (window.lucide) lucide.createIcons();
    }

    renderColorList(); // Initial render

    window.previewSwatch = function(index, input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(`swatch_preview_${index}`);
                const icon = document.getElementById(`swatch_icon_${index}`);
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.removeColor = function(index) {
        productColors.splice(index, 1);
        renderColorList();
    };

    // Detection Logic integration
    const colorDetectFile = document.getElementById('color_detect_file');
    const colorDetectPreview = document.getElementById('color_detect_preview');
    const detectHint = document.getElementById('detect_hint');

    colorDetectFile.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (event) => {
            colorDetectPreview.src = event.target.result;
            colorDetectPreview.classList.remove('hidden');
            detectHint.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    colorDetectPreview.addEventListener('click', function(e) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = this.naturalWidth; canvas.height = this.naturalHeight;
        ctx.drawImage(this, 0, 0);
        const rect = this.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * this.naturalWidth;
        const y = ((e.clientY - rect.top) / rect.height) * this.naturalHeight;
        const pixel = ctx.getImageData(x, y, 1, 1).data;
        const colorName = getNearestColorName(pixel[0], pixel[1], pixel[2]);
        addColor(colorName);
    });

    // Initial filter on load (especially for Edit page)
    window.addEventListener('load', () => {
        filterCategories();
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        if (selectedOption) {
            Alpine.find(mainForm).selectedCategoryPage = selectedOption.getAttribute('data-page') || '';
            Alpine.find(mainForm).selectedCategoryId = categorySelect.value;
        }

    });

    function updateImageOrder() {
        const items = document.querySelectorAll('#sortable-gallery > div');
        const order = Array.from(items).map(item => item.getAttribute('data-id'));
        const orderInput = document.getElementById('image_order');
        if (orderInput) orderInput.value = order.join(',');
        
        // Update badges
        items.forEach((item, index) => {
            const badge = item.querySelector('.index-badge');
            if (badge) badge.textContent = index + 1;
        });
    }

    function deleteExistingImage(event, form) {
        event.preventDefault();
        if (!confirm('Remove image?')) return;
        
        const url = form.action;
        const card = form.closest('[data-id]');
        
        fetch(url, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                card.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    card.remove();
                    updateImageOrder();
                }, 300); // 300ms transition
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: If AJAX fails, do a normal confirm/submit
            if (confirm('AJAX failed. Submit normally?')) {
                form.submit();
            }
        });
    }
</script>

<!-- CKEditor 4 Full -->
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<style>
    /* Adjust CKEditor styles to match theme */
    .cke_chrome {
        border-radius: 12px !important;
        border-color: #e5e7eb !important;
        overflow: hidden !important;
    }
    .cke_top {
        background: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    .cke_notifications_area {
        display: none !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('editor', {
                height: 300,
                removeButtons: 'PasteFromWord',
                // Ensure colors are available
                colorButton_enableMore: true,
                // Optional: match your site's fonts
                font_names: 'Inter/Inter, sans-serif; Arial/Arial, Helvetica, sans-serif; Georgia/Georgia, serif; Times New Roman/Times New Roman, Times, serif; Verdana/Verdana, Geneva, sans-serif'
            });
        }
    });
</script>
@endpush

@endsection
