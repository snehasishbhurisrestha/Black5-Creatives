<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CategoryFreeOffer extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'category_id',
        'required_qty',
        'free_product_qty',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function items()
    {
        return $this->hasMany(CategoryFreeOfferItem::class, 'offer_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('step_images');
        $this->addMediaCollection('offer_image')->singleFile();
        $this->addMediaCollection('success_image')->singleFile();
    }
}
