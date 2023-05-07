<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    protected $table="carts";
      protected $fillable = ['user_id','store_id','count','total','message','discount_type','discount_value','discount_total','free_shipping','discount_expire_date','is_deleted'];
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
