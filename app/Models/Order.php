<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
     protected $fillable = ['order_number','user_id','quantity','total_price','tax','discount','order_status','payment_status','status','is_deleted'];

 public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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
}
