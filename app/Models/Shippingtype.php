<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippingtype extends Model
{
    use HasFactory;
    protected $fillable = ['name','price','overprice','time', 'status', 'image', 'cod', 'is_deleted'];
    protected $casts = [
        'price' => 'float',
        'overprice' => 'float',
    ];
    public function stores()
    {
        return $this->belongsToMany(
            Store::class,
            'shippingtypes_stores',
            'shippingtype_id',
            'store_id'

        )->withPivot('price');
    }
    public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/shippingtype', 'public');
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
        return asset('storage/images/shippingtype') . '/' . $image;
    }
    public function shippingcities()
    {
        return $this->belongsToMany(ShippingCity::class, 'shippingcities_shippingtypes', 'Shippingtype_id', 'shipping_city_id');
    }

}
