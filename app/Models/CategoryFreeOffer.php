<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFreeOffer extends Model
{
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
}
