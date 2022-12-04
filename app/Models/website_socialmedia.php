<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class website_socialmedia extends Model
{
    use HasFactory;
    protected $fillable = ['name','logo','link','status','is_deleted'];

    public function setLogoAttribute($logo)
    {
        if (!is_null($logo)) {
            if (gettype($logo) != 'string') {
                $i = $logo->store('images/socialmedia_logo', 'public');
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
        return asset('storage/images/socialmedia_logo') . '/' . $logo;
    }
}