<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['paymenDate','paymentType','paymentTransectionID','paymentCardID','deduction','price_after_deduction','orderID','store_id'];
    protected $casts = [
        'deduction' => 'float',
        'price_after_deduction' => 'float',
    
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }

}
