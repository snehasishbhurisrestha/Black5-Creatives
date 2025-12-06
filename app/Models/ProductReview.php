<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductReview extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'review_text',
        'video_link'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
