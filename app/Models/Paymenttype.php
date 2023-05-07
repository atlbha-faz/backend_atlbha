<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymenttype extends Model
{
    use HasFactory;
     protected $fillable = ['name','image','status','is_deleted'];

    public function stores()
    {
     return $this->belongsToMany(
        Store::class,
        'paymenttypes_stores',
        'paymentype_id',
        'store_id'

        );
    }
     public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/paymenttype', 'public');
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
        return asset('storage/images/paymenttype') . '/' . $image;
    }

   public function offers()
  {
     return $this->belongsToMany(
        Offer::class,
        'offers_paymenttypes',
        'paymenttype_id',
        'offer_id'
        );
  }
}
