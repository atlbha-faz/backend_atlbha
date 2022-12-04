<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
     protected $fillable = ['name','name_en','image','status','is_deleted'];
     public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/currency', 'public');
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
        return asset('storage/images/currency') . '/' . $image;
    }
}