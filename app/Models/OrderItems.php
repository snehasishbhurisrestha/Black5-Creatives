<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrderItems extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function productVariationOption()
    {
        return $this->belongsTo(ProductVariationOption::class, 'product_variation_options_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('choice_image')->singleFile();
        $this->addMediaCollection('cart_images');
    }

}
