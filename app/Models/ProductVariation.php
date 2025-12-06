<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = ['product_id', 'name'];

    protected $appends = ['default_option'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->hasMany(ProductVariationOption::class, 'variation_id');
    }

    public function getDefaultOptionAttribute()
    {
        $defaultOption = $this->options()->first();

        if (!$defaultOption) {
            return null;
        }

        // If options use Spatie Media Library for images
        if (method_exists($defaultOption, 'getFirstMediaUrl')) {
            $defaultOption->image_url = $defaultOption->getFirstMediaUrl('variation-options-media');
        }

        return $defaultOption;
    }
}
