<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shippingcities_shippingtypes extends Model
{
    use HasFactory;

    protected $fillable = ['shipping_city_id', 'shippingtype_id'];
}
