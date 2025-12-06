<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $fillable = [
        'product_id',
        'brand_id',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function brands()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
