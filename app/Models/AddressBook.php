<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    use HasFactory; 

    protected $fillable = [
        'user_id',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone_number',
        'billing_address',
        'billing_country',
        'billing_state',
        'billing_city',
        'billing_zip_code',
        'addl_info',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_email',
        'shipping_phone_number',
        'shipping_address',
        'shipping_country',
        'shipping_state',
        'shipping_city',
        'shipping_zip_code',
        'is_default',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'billing_country', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'billing_state', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'billing_city', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'address_book_id');
    }
}
