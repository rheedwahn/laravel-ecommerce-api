<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;

    public function shipping_methods()
    {
        return $this->belongsToMany(ShippingMethod::class);
    }
}
