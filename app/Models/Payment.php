<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['paymenDate','paymentType','paymentTransectionID','paymentCardID','orderID'];
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }

}
