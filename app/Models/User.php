<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->id;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::creating(function ($user) {
            $user->password = bcrypt($user->password);
        });
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function cart()
    {
        return $this->belongsToMany(ProductVariation::class, 'cart_user')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

}
