<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class offers_products extends Model
{
    use HasFactory;
     protected $table="offers_products";
    protected $fillable = ['offer_id','product_id','type'];
}
