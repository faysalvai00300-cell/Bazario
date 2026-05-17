<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'image', 'view_all_image', 'linked_category_id', 'color', 'description', 'is_active', 'sort_order', 'meta_title', 'meta_description', 'meta_keywords', 'target_page', 'target_box', 'target_gender'];

    protected $casts = [
        'is_active' => 'boolean',
        'target_gender' => 'array'
    ];

    public function linkedCategory()
    {
        return $this->belongsTo(Category::class, 'linked_category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function linkedProducts()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function allProducts()
    {
        return $this->applyAllProductsFilter(Product::query());
    }

    public function applyAllProductsFilter($query)
    {
        return $query->where(function ($q) {
            $q->where('products.category_id', $this->id)
                ->orWhereExists(function ($q) {
                    $q->select(\Illuminate\Support\Facades\DB::raw(1))
                        ->from('category_product')
                        ->whereColumn('category_product.product_id', 'products.id')
                        ->where('category_product.category_id', $this->id);
                });
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return "https://picsum.photos/seed/cat-{$this->id}/400/400";
        }
        
        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (\Illuminate\Support\Str::startsWith($this->image, '/')) {
            return asset(ltrim($this->image, '/'));
        }

        return asset('storage/' . $this->image);
    }

    public function getViewAllImageUrlAttribute()
    {
        if (!$this->view_all_image) {
            return null;
        }
        
        if (\Illuminate\Support\Str::startsWith($this->view_all_image, ['http://', 'https://'])) {
            return $this->view_all_image;
        }

        if (\Illuminate\Support\Str::startsWith($this->view_all_image, '/')) {
            return asset(ltrim($this->view_all_image, '/'));
        }

        return asset('storage/' . $this->view_all_image);
    }
}
