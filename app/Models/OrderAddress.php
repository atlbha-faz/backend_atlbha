<?php

namespace App\Models;

use App\Models\Shippingtype;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderAddress extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'email', 'phonenumber', 'street_address', 'city', 'postal_code', 'district','shippingtype_id', 'user_id', 'type', 'default_address'];
    // public function order()
    // {
    //     return $this->belongsTo(Order::class, 'order_id', 'id');
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
      public function shippingtype()
    {
        return $this->belongsTo(Shippingtype::class, 'shippingtype_id', 'id');
    }
       public function orders()
    {
       return $this->belongsToMany(
        Orders::class,
            'orders_order_addresses',
            'order_address_id',
            'order_id'
            );
    }


}
