<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Cart extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['user_id', 'product_id', 'product_variation_options_id', 'product_variation_options_id2', 'quantity', 'brand_name', 'model_name'];

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

    public function variation2()
    {
        return $this->belongsTo(ProductVariationOption::class, 'product_variation_options_id2');
    }
}
