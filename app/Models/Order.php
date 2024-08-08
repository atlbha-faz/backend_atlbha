<?php

namespace App\Models;

use App\Models\ReturnOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_number', 'store_id', 'user_id', 'quantity', 'total_price', 'tax', 'shipping_price', 'discount', 'paymentype_id','weight', 'shippingtype_id', 'order_status', 'payment_status', 'is_deleted','cod','created_at', 'is_archive','description','subtotal','is_refund','overweight_price','is_return','totalCount'];
    protected $attributes = [
        'is_archive' =>false,
    ];
    protected $casts = [
        'total_price' => 'float',
        'subtotal' => 'float',
        'weight' => 'float',
         'totalCount'=>'integer',
        'discount' => 'float',
        'shipping_price' => 'float',
        'tax' => 'float',    
        'quantity'=>'integer',
        'overweight_price' => 'float'
    ];
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

    // public function products()
    // {
    //     return $this->belongsToMany(
    //         Product::class,
    //         'order_items',
    //         'order_id',
    //         'product_id'
    //     );
    // }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function returnOrders()
    {
        return $this->hasMany(ReturnOrder::class);
    }
  

    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class,'orderID', 'id');
    }
    public function paymentype()
    {
        return $this->belongsTo(Paymenttype::class, 'paymentype_id', 'id');
    }
    public function shippingtype()
    {
        return $this->belongsTo(Shippingtype::class, 'shippingtype_id', 'id');
    }
       public function order_addresses()
    {
       return $this->belongsToMany(
        OrderAddress::class,
            'orders_order_addresses',
            'order_id',
            'order_address_id'
       )->withPivot('type');
    }
    public function sumReturns($id){
        $returns = ReturnOrder::where('order_id', $id)->get();
        $prices = 0;
        foreach ($returns as $return) {
            $prices = $prices + ($return->qty * $return->orderItem->price);         
        }  
        return $prices;
        
    }

   
}
