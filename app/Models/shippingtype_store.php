<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shippingtype_store extends Model
{
    use HasFactory;
    protected $table="shippingtypes_stores";
    protected $fillable = ['shippingtype_id','store_id','price','time'];
}
