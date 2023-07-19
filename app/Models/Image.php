<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

      protected $fillable=[
        'image',
        'product_id',
        'is_deleted'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
    
      public function getImageAttribute($image)
    {
        if (is_null($image)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/product') . '/' . $image;
    }
}
