<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFreeOfferItem extends Model
{
    protected $fillable = [
        'offer_id',
        'variation_option_id',
        'free_qty'
    ];

    public function offer()
    {
        return $this->belongsTo(CategoryFreeOffer::class, 'offer_id');
    }

    public function variationOption()
    {
        return $this->belongsTo(ProductVariationOption::class, 'variation_option_id');
    }

}
