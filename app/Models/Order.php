<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
     protected $fillable = ['order_number','user_id','city_id','quantity','total_price','tax','shipping_price','discount','order_status','payment_status'];

 public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

     public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

       public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

       public function products()
    {
          return $this->belongsToMany(
          Product::class,
          'order_items',
          'order_id',
          'product_id'
     );
    }

     public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
