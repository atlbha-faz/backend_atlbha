<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
     protected $fillable = ['order_id','product_id','store_id','user_id','quantity','price','total_price','discount','order_status','payment_status'];

 public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

     public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

  

     public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

}
