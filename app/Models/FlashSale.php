<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = ['product_id', 'sale_price', 'quantity', 'sold', 'starts_at', 'ends_at', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'sale_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getProgressAttribute()
    {
        if ($this->quantity == 0) return 0;
        return min(100, round(($this->sold / $this->quantity) * 100));
    }

    public function getRemainAttribute()
    {
        return max(0, $this->quantity - $this->sold);
    }
}
