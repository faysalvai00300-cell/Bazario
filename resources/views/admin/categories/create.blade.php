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
</script>
@endpush
@endsection
