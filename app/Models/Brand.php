<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'description', 'is_visible', 'is_home', 'is_popular', 'is_menu', 'is_special'];

    protected $appends = ['image_link'];

    public function parent()
    {
        return $this->belongsTo(Brand::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Brand::class, 'parent_id');
    }

    public function getParentCategoryNameAttribute()
    {
        return $this->parent ? $this->parent->name : null;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageLinkAttribute()
    {
        return $this->getFirstMediaUrl('brand') ?: null;
    }
}
