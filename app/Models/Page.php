<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'meta_title', 
        'meta_keywords', 
        'meta_description', 
        'is_visible'
    ];

    // Auto-generate slug from name
    protected static function booted()
    {
        static::creating(function ($page) {
            $page->slug = Str::slug($page->name);
        });

        static::updating(function ($page) {
            $page->slug = Str::slug($page->name);
        });
    }
}
