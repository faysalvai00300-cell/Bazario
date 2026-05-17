<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'name', 'email', 'ip_address', 'phone', 'address', 'thana', 'city',
        'state', 'postal_code', 'subtotal', 'discount', 'shipping', 'total',
        'payment_method', 'payment_status', 'transaction_id', 'payment_phone', 'status', 'promo_code', 'notes', 'admin_note', 'delivered_at',
        'courier_name', 'courier_status', 'courier_tracking_id'
    ];

    protected $casts = ['delivered_at' => 'datetime'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-cyan-100 text-cyan-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
