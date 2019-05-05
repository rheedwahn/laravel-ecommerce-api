<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'country_id', 'name', 'address_1', 'city', 'postal_code', 'default'
    ];

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::creating(function ($address) {
            if($address->default) {
                $address->user->addresses()->update([
                    'default' => false
                ]);
            }
        });
    }

    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = ($value === 'true' || $value ? true : false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function switchToDefault($address_id)
    {
        $address = $this->user->addresses()->find($address_id);
        $address->user->addresses()->update([
            'default' => false
        ]);
        $address->default = true;
        $address->save();
        return $address;
    }

    public function deleteAddress($address_id)
    {
        return $this->user->addresses()->find($address_id)->delete() ? true : false;
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
