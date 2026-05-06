@extends('layouts.admin')
@section('title', 'Add Middle Banner')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add New Middle Banner</h2>
    <a href="{{ route('admin.middle-banners.index') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium dark:text-gray-400 dark:hover:text-gray-300 flex items-center gap-1 group">
        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i> Back to Middle Banners
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-3xl dark:bg-gray-800 dark:border-gray-700">
    <form action="{{ route('admin.middle-banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-100 dark:bg-red-900/20 dark:border-red-900/30">
            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Banner Image URL (Optional Fallback)</label>
                <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/banner.jpg"
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 mb-2 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                <p class="text-gray-400 text-xs dark:text-gray-500">If provided, this URL will be used instead of an uploaded file.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Banner Image <span class="text-red-500">*</span></label>
                <input type="file" name="image" id="image_input" accept="image/*"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:file:bg-orange-900/30 dark:file:text-orange-400">
                <p class="text-gray-400 text-xs mt-1 dark:text-gray-500">Recommended size: 1200x300px (Wide), max 3MB.</p>

                <!-- Image Preview Area -->
                <div id="image_preview_container" class="mt-3 hidden">
                    <img id="image_preview" src="" alt="Banner Preview" class="h-48 w-full object-cover rounded-2xl border-2 border-dashed border-[#FF6A00]/50 p-1.5 bg-orange-50/30 dark:bg-orange-900/10">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Title (Heading)</label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                        class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="e.g. Get 30% OFF">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Subtitle / Description</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" 
                        class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Short description or code">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Badge Text (e.g. Limited Offer)</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text') }}" 
                        class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Text above title">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text') }}" 
                        class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="e.g. Shop Now">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Link URL</label>
                <input type="text" name="link" value="{{ old('link') }}" 
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="e.g. /category/electronics">
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 space-y-4">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="show_on_desktop" value="0">
                    <input type="checkbox" name="show_on_desktop" id="show_on_desktop" value="1" {{ old('show_on_desktop', true) ? 'checked' : '' }} 
                        class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700">
                    <label for="show_on_desktop" class="font-semibold text-gray-800 cursor-pointer dark:text-gray-300">Show on PC</label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="show_on_mobile" value="0">
                    <input type="checkbox" name="show_on_mobile" id="show_on_mobile" value="1" {{ old('show_on_mobile', true) ? 'checked' : '' }} 
                        class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700">
                    <label for="show_on_mobile" class="font-semibold text-gray-800 cursor-pointer dark:text-gray-300">Show on Mobile</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                        class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700">
                    <label for="is_active" class="font-semibold text-gray-800 cursor-pointer dark:text-gray-300">Display as Active Promo</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('admin.middle-banners.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-900">Cancel</a>
            <button type="submit" class="admin-primary-btn px-6 py-2.5 rounded-xl font-bold transition-colors shadow-sm">Publish Middle Banner</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('image_input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('image_preview_container');
        const previewImage = document.getElementById('image_preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.src = '';
            previewContainer.classList.add('hidden');
        }
    });
</script>
@endpush

@endsection
