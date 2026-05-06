<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    protected $fillable = [
        'name',
        'charge',
        'is_active',
        'sort_order',
    ];
}
