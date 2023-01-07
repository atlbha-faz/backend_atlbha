<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Importproduct extends Model
{
    use HasFactory;
     protected $fillable = ['product_id','store_id','price'];
       public function store()
    {
        return $this->belongsTo(Store::class);
    }
      public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
