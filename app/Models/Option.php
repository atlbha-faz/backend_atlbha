<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
  use HasFactory;
      protected $fillable=[
        'name',
        'quantity',
        'price',
        'discount_price',
        'product_id',
        'status',
        'less_qty',
        'is_deleted',
        'default_option',
        'original_id',
        'importproduct_id'
    ];
    protected $casts = [
      'default_option'=>'integer'
    ];
     public function product(){
        return $this->belongsTo(Product::class);
    }
    public function importproduct()
    {
        return $this->belongsTo(Importproduct::class);
    }
    protected function name() : Attribute{
    return Attribute::make(
      get : fn($value) =>json_decode($value,true),
      set : fn($value) =>json_encode($value),
    );
    }
}