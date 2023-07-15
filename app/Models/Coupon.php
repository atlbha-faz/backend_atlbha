<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
        protected $fillable = ['code','discount_type','total_price','discount','expire_date','total_redemptions','user_redemptions','start_at','free_shipping','exception_discount_product','coupon_apply','store_id','status','is_deleted'];


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

 public function expireCoupon($id)
 {
    $expire = Coupon::query()->find($id);
    // $expire=Coupon::select('expire_date')->where('id',$coupon);

    $my_time=Carbon::now();
$status = "غير نشط";
    if($expire->status=='active'){
       $status = "نشط";
    if($expire->expire_date < $my_time){
$expire->update(['status' => 'expired']);
        $status = "منتهي";
}
    }
//
if($expire->status=='expired'){
    $status = "منتهي";
    if($expire->expire_date > $my_time){
$status = "نشط";
$expire->update(['status' => 'active']);}
    }


return $status;


 }

  public function categories()
  {
     return $this->belongsToMany(
        Category::class,
        'categories_coupons',
        'coupon_id',
        'category_id'
        );
  }


public function products()
  {
     return $this->belongsToMany(
        Product::class,
        'coupons_products',
        'coupon_id',
        'product_id'
     );
  }
public function imports()
  {
     return $this->belongsToMany(
        Product::class,
        'coupons_products',
        'coupon_id',
        'product_id'
        );
  }

public function paymenttypes()
  {
     return $this->belongsToMany(
        Paymenttype::class,
        'coupons_paymenttypes',
        'coupon_id',
        'paymenttype_id'
        );
  }

}
