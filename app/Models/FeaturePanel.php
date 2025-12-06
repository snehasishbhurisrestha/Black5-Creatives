<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FeaturePanel extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'is_visible',
    ];

    /**
     * Get the category that this feature panel belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
