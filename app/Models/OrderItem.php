<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_id', 'store_id', 'user_id', 'quantity', 'price', 'total_price', 'discount','option_id', 'order_status', 'payment_status'];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function subtotal($orderitem_id)
    {
        $quantity = OrderItem::where('id', $orderitem_id)->pluck('quantity')->first();
        $price = OrderItem::where('id', $orderitem_id)->pluck('price')->first();
        $orderitem_subtotal = $quantity * $price;
        return $orderitem_subtotal;
    }
}
