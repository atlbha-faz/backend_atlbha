<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packagecoupon extends Model
{
    use HasFactory;
    protected $fillable = ['code','discount_type','discount','start_date','expire_date','total_redemptions','status','is_deleted'];

}