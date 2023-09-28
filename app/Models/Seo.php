<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;
    protected $fillable = ['google_analytics', 'snappixel', 'tiktokpixel', 'twitterpixel', 'instapixel', 'metatags', 'key_words', 'store_id', 'status', 'is_deleted'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function setMetatagsAttribute($metatags)
    {

        if (!is_null($metatags)) {
            if (gettype($metatags) != 'string') {
                $i = $metatags->store('files/store', 'public');
                $this->attributes['metatags'] = $metatags->hashName();
            } else {
                $this->attributes['metatags'] = $metatags;
            }
        }
    }

    public function getMetatagsAttribute($metatags)
    {

        if (is_null($metatags)) {
            return asset('assets/media/test.txt');
        }
        return asset('storage/files/store') . '/' . $metatags;
    }
    public function setSnappixelAttribute($snappixel)
    {

        if (!is_null($snappixel)) {
            if (gettype($snappixel) != 'string') {
                $i = $snappixel->store('files/store', 'public');
                $this->attributes['snappixel'] = $snappixel->hashName();
            } else {
                $this->attributes['snappixel'] = $snappixel;
            }
        }
    }

    public function getSnappixelAttribute($snappixel)
    {

        if (is_null($snappixel)) {
            return asset('assets/media/test.txt');
        }
        return asset('storage/files/store') . '/' . $snappixel;
    }
    public function setTiktokpixelAttribute($tiktokpixel)
    {

        if (!is_null($tiktokpixel)) {
            if (gettype($tiktokpixel) != 'string') {
                $i = $tiktokpixel->store('files/store', 'public');
                $this->attributes['tiktokpixel'] = $tiktokpixel->hashName();
            } else {
                $this->attributes['tiktokpixel'] = $tiktokpixel;
            }
        }
    }

    public function getTiktokpixelAttribute($tiktokpixel)
    {

        if (is_null($tiktokpixel)) {
            return asset('assets/media/test.txt');
        }
        return asset('storage/files/store') . '/' . $tiktokpixel;
    }
    public function setTwitterpixelAttribute($twitterpixel)
    {

        if (!is_null($twitterpixel)) {
            if (gettype($twitterpixel) != 'string') {
                $i = $twitterpixel->store('files/store', 'public');
                $this->attributes['twitterpixel'] = $twitterpixel->hashName();
            } else {
                $this->attributes['twitterpixel'] = $twitterpixel;
            }
        }
    }

    public function getTwitterpixelAttribute($twitterpixel)
    {

        if (is_null($twitterpixel)) {
            return asset('assets/media/test.txt');
        }
        return asset('storage/files/store') . '/' . $twitterpixel;
    }
    public function setInstapixelAttribute($instapixel)
    {

        if (!is_null($instapixel)) {
            if (gettype($instapixel) != 'string') {
                $i = $instapixel->store('files/store', 'public');
                $this->attributes['instapixel'] = $instapixel->hashName();
            } else {
                $this->attributes['instapixel'] = $instapixel;
            }
        }
    }

    public function getInstapixelAttribute($instapixel)
    {

        if (is_null($instapixel)) {
            return asset('assets/media/test.txt');
        }
        return asset('storage/files/store') . '/' . $instapixel;
    }
}
