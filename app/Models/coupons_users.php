<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class coupons_users extends Model
{
    use HasFactory;
    protected $table = "coupons_users";
    protected $fillable = ['coupon_id', 'user_id'];
}
