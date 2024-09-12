<?php

namespace App\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'image', 'parent_id'];
   

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
    public function package()
    {
        return $this->hasOne(Package::class);
    }
}
