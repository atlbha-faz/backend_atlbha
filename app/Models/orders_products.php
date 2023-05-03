<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders_products extends Model
{
    use HasFactory;
    protected $table="orders_products";
  protected $fillable = ['order_id','product_id','qty','price','subtotal'];
      protected $casts = [
  'product_id' => 'array',
];
}
