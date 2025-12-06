<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = ['name', 'slug', 'parent_id', 'description', 'is_visible', 'is_home', 'is_popular', 'is_menu', 'is_special'];

    protected $appends = ['image_link','banner_image_link'];
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getParentCategoryNameAttribute()
    {
        return $this->parent ? $this->parent->name : null;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }

    public function getImageLinkAttribute()
    {
        return $this->getFirstMediaUrl('category') ?: null;
    }

    public function getBannerImageLinkAttribute()
    {
        return $this->getFirstMediaUrl('category-banner') ?: null;
    }

    public function featurePanels()
    {
        return $this->hasMany(FeaturePanel::class);
    }
}
