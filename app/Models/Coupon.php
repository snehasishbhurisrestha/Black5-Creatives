<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Coupon extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'code',
        'type',

        'value',
        'override_price',

        'minimum_purchase',
        'min_qty',

        'category',        // case | wall_art
        'product_type',    // hybrid | soft | hard | frame_premium etc.

        'buy_qty',
        'get_qty',
        'free_product_type',

        'start_date',
        'end_date',

        'usage_type',
        'is_active',

        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean'
    ];
}
