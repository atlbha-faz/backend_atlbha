<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','link','email','phoneNumber','logo','icon','address',
'country_id','city_id','status','is_deleted'];
public function city()
{
    return $this->belongsTo(City::class, 'city_id', 'id');
}
      public function country()
{
    return $this->belongsTo(Country::class, 'country_id', 'id');

}
public function store()
    {
        return $this->hasMany(Store::class);
    }
    public function SocialMedia()
    {
        return $this->hasMany(SocialMedia::class);
    }

    public function setLogoAttribute($logo)
    {
        if (!is_null($logo)) {
            if (gettype($logo) != 'string') {
                $i = $logo->store('images/logo', 'public');
                $this->attributes['logo'] = $logo->hashName();
            } else {
                $this->attributes['logo'] = $logo;
            }
        }
    }

    public function getLogoAttribute($logo)
    {
        if (is_null($logo)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/logo') . '/' . $logo;
    }
     public function setIconAttribute($icon)
    {
        if (!is_null($icon)) {
            if (gettype($icon) != 'string') {
                $i = $icon->store('images/icon', 'public');
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
        return asset('storage/images/icon') . '/' . $icon;
    }

}
