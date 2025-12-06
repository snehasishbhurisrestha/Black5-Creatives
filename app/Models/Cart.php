<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'product_variation_options_id', 'quantity', 'brand_name', 'model_name'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariationOption()
    {
        return $this->belongsTo(ProductVariationOption::class, 'product_variation_options_id');
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariationOption::class, 'product_variation_options_id');
    }
}
