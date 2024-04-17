<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Importproduct extends Model
{
    use HasFactory;
     protected $fillable = ['product_id','store_id','price','qty','special','discount_price_import','status'];
       public function store()
    {
        return $this->belongsTo(Store::class);
    }
      public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function option()
    {
        return $this->hasMany(Option::class,'importproduct_id','id');
    }

}
