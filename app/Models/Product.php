<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [ 'name','meta_title','meta_keywords','meta_description', 'slug', 'brand_id', 'product_type', 'short_description', 'long_description',
        'price', 'price', 'discount_rate', 'discount_price', 'gst_rate', 'gst_amount', 'total_price', 'stock', 'sku', 'barcode',
        'weight', 'dimensions','is_home', 'is_special', 'is_featured', 'is_best_selling', 'is_active'
    ];

    protected $appends = ['image_link'];
    
    public function product_subcategories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'product_brands', 'product_id', 'brand_id');
    }

    // public function brand()
    // {
    //     return $this->belongsTo(Brand::class);
    // }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageLinkAttribute()
    {
        $mainImage = $this->getMedia('products-media')
                ->firstWhere('custom_properties.is_main', true);

        return $mainImage ? $mainImage->getUrl() : null;
    }

    public function recentlyViewedBy()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }
}
