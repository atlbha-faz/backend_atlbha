<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCity extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'name_en', 'status', 'region_id', 'country_id', 'is_deleted'];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function shippingtypes()
    {
        return $this->belongsToMany(Shippingtype::class, 'shippingcities_shippingtypes', 'shipping_city_id', 'Shippingtype_id');
    }
}
