<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class offers_paymenttypes extends Model
{
    use HasFactory;
    protected $table="offers_paymenttypes";
    protected $fillable = ['offer_id','paymenttype_id','type'];

}
