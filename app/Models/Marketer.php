<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Marketer extends Model
{
    use HasFactory;
      protected $fillable = ['name','email','password','image','user_name','phonenumbe','snapchat','facebook','twiter','whatsapp','youtube','instegram','socialmediatext','city_id','country_id','status','is_deleted'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
          public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/marketer', 'public');
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
        return asset('storage/images/marketer') . '/' . $image;
    }
}
