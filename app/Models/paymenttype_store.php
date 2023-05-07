<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymenttype_store extends Model
{
    use HasFactory;
    protected $table="paymenttypes_stores";
    protected $fillable = ['paymentype_id','store_id'];
}
