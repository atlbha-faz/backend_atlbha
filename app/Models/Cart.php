<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = "carts";
    protected $fillable = ['user_id', 'store_id', 'count', 'total', 'message', 'discount_type', 'subtotal', 'totalCount', 'discount_value', 'shipping_price', 'tax', 'weight', 'discount_total', 'free_shipping', 'discount_expire_date','overweight_price', 'is_deleted','coupon_id'];
    protected $casts = [
        'total' => 'float',
        'subtotal' => 'float',
        'count'=>'integer',
         'totalCount'=>'integer',
        'weight' => 'float',
        'discount_value' => 'float',
        'discount_total' => 'float',
        'shipping_price' => 'float',
        'tax' => 'float',
        'overweight_price' => 'float'
    ];
      public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
         return $this->belongsTo(Store::class);

    }
    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }




}
