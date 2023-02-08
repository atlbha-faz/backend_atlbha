<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package_store extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table="packages_stores";
         protected $fillable = ['package_id','store_id','start_at','end_at','periodtype','packagecoupon_id','status','is_deleted'];
         protected $casts = [
            'package_id' => 'array',
        ];
}