<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order', 'max_discount',
        'usage_limit', 'usage_count', 'expires_at', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean', 'expires_at' => 'date'];

    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->isExpired()) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        return true;
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function calculateDiscount($subtotal)
    {
        if ($subtotal < $this->min_order) return 0;
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount) $discount = min($discount, $this->max_discount);
            return $discount;
        }
        return min($this->value, $subtotal);
    }
}
