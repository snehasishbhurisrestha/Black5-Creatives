<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 
        'type', 
        'value', 
        'minimum_purchase', 
        'start_date', 
        'end_date', 
        'usage_type', 
        'is_active',
    ];
}
