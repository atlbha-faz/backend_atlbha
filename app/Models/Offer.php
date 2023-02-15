<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
 protected $fillable = ['offer_type','offer_title','offer_view','start_at','end_at','purchase_quantity','purchase_type','get_quantity','get_type','offer1_type','discount_percent','discount_value_offer2','offer_apply','offer_type_minimum','offer_amount_minimum','coupon_status','discount_value_offer3','maximum_discount','store_id','status','is_deleted'];
 public function store()
 {
     return $this->belongsTo(Store::class);
 }

 public function categories()
  {
     return $this->belongsToMany(
        Category::class,
        'categories_offers',
        'offer_id',
        'category_id'
        )->withPivot("type");
  }


public function products()
  {
     return $this->belongsToMany(
        Product::class,
        'offers_products',
        'offer_id',
        'product_id'
        )->withPivot("type");
  }

public function paymenttypes()
  {
     return $this->belongsToMany(
        Paymenttype::class,
        'offers_paymenttypes',
        'offer_id',
        'paymenttype_id'
        );
  }
}
