<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Offer extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'start_time',
        'end_time',
        'type',
        'benefits',
        'status'
    ];

    protected $casts = [
        'benefits' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offer) {
            $offer->slug = Str::slug($offer->name) . '-' . Str::random(5);
        });
    }

    /**
     * Relationship: Offer has many OfferProduct entries (pivot rows)
     */
    public function offerProducts()
    {
        return $this->hasMany(OfferProduct::class);
    }

    /**
     * Relationship: Offer belongs to many Products (through pivot)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product')
                    ->using(OfferProduct::class)
                    ->withTimestamps();
    }

    /**
     * Check if offer is active
     */
    public function isActive(): bool
    {
        return $this->status && now()->between($this->start_time, $this->end_time);
    }
}
