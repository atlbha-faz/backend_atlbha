<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
        protected $fillable = ['code','discount_type','total_price','discount','expire_date','total_redemptions','user_redemptions','free_shipping','exception_discount_product','store_id','status','is_deleted'];


         public function store()
    {
        return $this->belongsTo(store::class, 'store_id','id');
    }
           public function users()
    {
       return $this->belongsToMany(
        User::class,
        'coupons_users',
        'coupon_id',
        'user_id'

        );
    }

}
