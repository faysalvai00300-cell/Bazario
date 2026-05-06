<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'subcategory_id', 'name', 'slug', 'description',
        'short_description', 'price', 'sale_price', 'stock', 'sku', 'thumbnail',
        'rating', 'review_count', 'is_featured', 'is_active', 'is_new', 'brand',
        'weight', 'specifications', 'sizes', 'colors', 'free_shipping',
        'meta_title', 'meta_description', 'meta_keywords', 'video_url', 'size_chart',
        'is_mega_deal', 'is_size_chart_active', 'color_swatches', 'color_image_indices', 'mega_deal_effect'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_new' => 'boolean',
        'specifications' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'free_shipping' => 'boolean',
        'size_chart' => 'array',
        'is_mega_deal' => 'boolean',
        'is_size_chart_active' => 'boolean',
        'color_swatches' => 'array',
        'color_image_indices' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function flashSales()
    {
        return $this->hasMany(FlashSale::class)->where('is_active', true)->where('ends_at', '>', now());
    }

    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getEffectivePriceAttribute()
    {
        // Use eager-loaded relationship if available to avoid N+1 queries
        $flash = $this->flashSales->first();
        if ($flash && isset($flash->sale_price)) {
            return $flash->sale_price;
        }
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentAttribute()
    {
        $effective = $this->effective_price;
        if ($effective < $this->price) {
            $percent = round((($this->price - $effective) / $this->price) * 100);
            return $percent > 0 ? (int)$percent : 1;
        }
        return 0;
    }

    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail) {
            return "https://picsum.photos/seed/{$this->id}/400/400";
        }
        
        // If it's a full URL, return as is
        if (\Illuminate\Support\Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
            return $this->thumbnail;
        }

        // If it starts with /, it's relative to public root (e.g. /images/products/...)
        if (\Illuminate\Support\Str::startsWith($this->thumbnail, '/')) {
            return asset(ltrim($this->thumbnail, '/'));
        }

        // Otherwise it's a local file relative to the storage disk
        return asset('storage/' . $this->thumbnail);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getColorImageUrl($colorName)
    {
        if (empty($colorName) || empty($this->colors) || !is_array($this->colors)) {
            return $this->thumbnail_url;
        }

        $index = array_search($colorName, $this->colors);
        if ($index === false) {
            return $this->thumbnail_url;
        }

        $indices = $this->color_image_indices ?? [];
        $imgIdx = isset($indices[$index]) && is_numeric($indices[$index]) ? (int)$indices[$index] : null;

        if ($imgIdx === null) {
            return $this->thumbnail_url;
        }

        // imgIdx 1 is thumbnail_url
        if ($imgIdx === 1) {
            return $this->thumbnail_url;
        }

        // imgIdx > 1 refers to product images (0-indexed collection)
        // so imgIdx 2 is images[0], imgIdx 3 is images[1], etc.
        $imageCollectionIndex = $imgIdx - 2;
        
        // Ensure images are loaded if needed, but usually we eager load them
        $image = $this->images->values()->get($imageCollectionIndex);

        return $image ? $image->image_url : $this->thumbnail_url;
    }

    public function getColorImagePath($colorName)
    {
        if (empty($colorName) || empty($this->colors) || !is_array($this->colors)) {
            return $this->thumbnail;
        }

        $index = array_search($colorName, $this->colors);
        if ($index === false) {
            return $this->thumbnail;
        }

        $indices = $this->color_image_indices ?? [];
        $imgIdx = isset($indices[$index]) && is_numeric($indices[$index]) ? (int)$indices[$index] : null;

        if ($imgIdx === null) {
            return $this->thumbnail;
        }

        // imgIdx 1 is thumbnail
        if ($imgIdx === 1) {
            return $this->thumbnail;
        }

        // imgIdx > 1 refers to product images (0-indexed collection)
        $imageCollectionIndex = $imgIdx - 2;
        $image = $this->images->values()->get($imageCollectionIndex);

        return $image ? $image->image : $this->thumbnail;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
