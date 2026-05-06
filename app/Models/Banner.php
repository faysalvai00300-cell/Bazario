<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'image', 'mobile_image', 'link',
        'button_text', 'badge_text', 'is_active', 'show_on_mobile', 'show_on_desktop', 'sort_order', 'type'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_on_mobile' => 'boolean',
        'show_on_desktop' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;
        
        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (\Illuminate\Support\Str::startsWith($this->image, '/')) {
            return asset(ltrim($this->image, '/'));
        }

        return asset('storage/' . $this->image);
    }

    public function getMobileImageUrlAttribute()
    {
        if (!$this->mobile_image) return null;

        if (\Illuminate\Support\Str::startsWith($this->mobile_image, ['http://', 'https://'])) {
            return $this->mobile_image;
        }

        if (\Illuminate\Support\Str::startsWith($this->mobile_image, '/')) {
            return asset(ltrim($this->mobile_image, '/'));
        }

        return asset('storage/' . $this->mobile_image);
    }
}
