<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    
    use HasFactory;
         protected $fillable = ['name','icon','status','is_deleted'];


 public function stores()
    {
         return $this->belongsToMany(
         Store::class,
        'activities_stores',
        'activity_id',
        'store_id'
        );
  }

    public function setIconAttribute($icon)
    {
        if (!is_null($icon)) {
            if (gettype($icon) != 'string') {
                $i = $icon->store('images/icon/activity', 'public');
                $this->attributes['icon'] = $icon->hashName();
            } else {
                $this->attributes['icon'] = $icon;
            }
        }
    }

    public function getIconAttribute($icon)
    {
        if (is_null($icon)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/icon/activity') . '/' . $icon;
    }

}

