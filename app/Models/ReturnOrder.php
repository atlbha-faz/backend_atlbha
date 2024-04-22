<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="return_orders";
    protected $fillable = ['order_id','order_item_id','price','qty','return_status','comment','return_reason_id','store_id'];
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class,'order_item_id','id');
    }
    public function returnReason()
    {
        return $this->belongsTo(ReturnReason::class,'return_reason_id','id');
    }
 
  
}
