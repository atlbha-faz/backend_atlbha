<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippingtype extends Model

    {
    use HasFactory;
    protected $fillable = ['name','status','is_deleted'];

      public function stores()
    {
     return $this->belongsToMany(
        Store::class,
        'shippingtypes_stores',
        'shippingtype_id',
        'store_id'

        );
    }





}
