<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute_product extends Model
{
    use HasFactory;
    protected $table="attributes_products";
    protected $fillable = ['attribute_id',' product_id','value'];
  
}
