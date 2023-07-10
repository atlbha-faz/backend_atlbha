<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class couponss_paymenttypes extends Model
{
    use HasFactory;
      protected $table="coupons_paymenttypes";
    protected $fillable = ['coupon_id','paymenttype_id','type'];
    protected $casts = [
        'paymenttype_id' => 'array',
    ];
}
