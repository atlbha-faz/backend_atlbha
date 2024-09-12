<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'image', 'parent_id' , 'package_id'];
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/trip', 'public');
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
        return asset('storage/images/trip') . '/' . $image;
    }
    public function details()
    {
        return $this->hasMany(Trip::class, 'parent_id');
    }
}
