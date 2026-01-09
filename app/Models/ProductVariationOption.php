<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductVariationOption extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['variation_id','variation_type','variation_name','value','price','stock','need_image'];
    protected $appends = ['images'];
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('variation-option');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getImagesAttribute()
    {
        if (!$this->variation_id) {
            return [];
        }

        // Query the product_id directly without touching the relationship chain
        $productId = ProductVariation::where('id', $this->variation_id)->value('product_id');
        if (!$productId) {
            return [];
        }

        $product = Product::find($productId);
        if (!$product) {
            return [];
        }

        $media = $product->getMedia('products-media')
            ->filter(fn($file) =>
                ($file->custom_properties['option_id'] ?? null) == $this->id
            );

        return $media->map(function ($file) {
            return [
                'id' => $file->id,
                'url' => $file->getUrl(),
                'is_main' => $file->custom_properties['is_main'] ?? false,
                'file_id' => $file->custom_properties['file_id'] ?? null,
            ];
        })->values();
    }

}
