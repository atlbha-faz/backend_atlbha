<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippingtype extends Model

    {
    use HasFactory;
    protected $fillable = ['name','status','image','cod','is_deleted'];

      public function stores()
    {
     return $this->belongsToMany(
        Store::class,
        'shippingtypes_stores',
        'shippingtype_id',
        'store_id'

        );
    }
      public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/shippingtype', 'public');
                $this->attributes['image'] = $image->hashName();
            } else {
                $this->attributes['image'] = $image;
            }
        }
    }

    public function getImageAttribute($image)
    {
        if (is_null($image)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/shippingtype') . '/' . $image;
    }





}
