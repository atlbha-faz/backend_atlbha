<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = ['description','price','store_id','city','streetaddress', 'sticker','shipping_id','track_id','district','shipping_type',
    'destination_city','destination_district','destination_streetaddress','shippingtype_id','weight','quantity','customer_id','order_id','is_deleted'];
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


}
