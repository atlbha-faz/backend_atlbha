<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class coupons_products extends Model
{
    use HasFactory;
   protected $table="coupons_products";
    protected $fillable = ['coupon_id','product_id',' import'];
    // protected $casts = [
    //     'product_id' => 'array',
    // ];
}
