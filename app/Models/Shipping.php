<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = ['description','price','quantity','weight','store_id','shipping_id','city','streetaddress', 'sticker','track_id','district',
    'customer_id','shippingtype_id','order_id','shipping_status','cashondelivary','is_deleted'];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
   
    public function shippingtype()
    {
        return $this->belongsTo(Shippingtype::class, 'shippingtype_id', 'id');
    }
}
