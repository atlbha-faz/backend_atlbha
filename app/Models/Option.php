<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
      protected $fillable=[
        'type',
        'title',
        'value',
        'product_id',
        'status',
        'is_deleted'
    ];
     public function product(){
        return $this->belongsTo(Product::class);
    }
    protected $casts = [
    'value' => 'array',
];
}