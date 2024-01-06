<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'lastname',
        'user_name',
        'email',
        'password',
        'image',
        'gender',
        'user_type',
        'phonenumber',
        'city_id',
        'device_token',
        'country_id',
        'code',
        'code_expires_at',
        'verify_code',
        'verify_code_expires_at',
        'store_id',
        'status',
        'is_deleted',
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
            'coupon_id'
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
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function marketer()
    {
        return $this->hasMany(Marketer::class);
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
            return asset('assets/media/man.png');
        }
        return asset('storage/images/users') . '/' . $image;
    }

    public function generateCode()
    {
        $this->timestamps = false;
        $this->code = rand(100000, 999999);
        $this->code_expires_at = now()->addMinutes(10);
        $this->save();
    }

    public function resetCode()
    {
        $this->timestamps = false;
        $this->code = null;
        $this->code_expires_at = null;
        $this->save();
    }

    public function generateVerifyCode()
    {
        $this->timestamps = false;
        $this->verify_code = rand(100000, 999999);
        $this->verify_code_expires_at = now()->addMinutes(1);
        $this->save();
    }

    public function resetVerifyCode()
    {
        $this->timestamps = false;
        $this->verify_code = null;
        $this->verify_code_expires_at = null;
        $this->save();
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

}
