<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOrderAddress extends Model
{
    use HasFactory;
    protected $table="orders_order_addresses";
    protected $fillable = ['order_id','order_address_id','type'];
}
