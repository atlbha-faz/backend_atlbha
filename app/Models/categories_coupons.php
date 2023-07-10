<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories_coupons extends Model
{
    use HasFactory;
     protected $table="categories_coupons";
    protected $fillable = ['coupon_id','category_id'];
    protected $casts = [
        'category_id' => 'array',
    ];
}
