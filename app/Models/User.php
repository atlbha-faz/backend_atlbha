<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'user_name',
        'email',
        'password',
        'image',
        'gender',
        'user_type',
        'phonenumber',
        'city_id',
        'country_id',
         'status',
       'is_deleted'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function coupons()
{
    return $this->belongsToMany(
        Coupon::class,
        'coupons_users',
        'user_id',
       'coupon_id',
        );
    }
       public function services()
{
    return $this->belongsToMany(
        Service::class,
        'services_users',
        'user_id',
        'service_id'
        );
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
          public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

     public function order()
    {
        return $this->hasMany(Order::class);
    }

       public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/users', 'public');
                $this->attributes['image'] = $image->hashName();
            } else {
                $this->attributes['image'] = $image;
            }
        }
    }

    public function getImageAttribute($image)
    {
        if (is_null($image)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/users') . '/' . $image;
    }

}
