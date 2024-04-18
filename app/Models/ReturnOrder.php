<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ReturnOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="return_orders";
    protected $fillable = ['order_id','user_id','product_id','option_id','price','qty','return_status','comment','reason_txt','store_id'];
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id', 'id');
    }
  
}
