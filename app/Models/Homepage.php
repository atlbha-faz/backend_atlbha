<?php

namespace App\Models;

use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homepage extends Model implements Viewable
{
    use HasFactory;
    use InteractsWithViews;
    protected $fillable = ['logo', 'logo_footer', 'banar1', 'banarstatus1', 'banar2', 'banarstatus2', 'banar3', 'banarstatus3', 'clientstatus', 'commentstatus',
        'slider1', 'sliderstatus1', 'slider2', 'sliderstatus2', 'slider3', 'sliderstatus3', 'store_id', 'is_deleted'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function setLogoAttribute($logo)
    {
        if (!is_null($logo)) {
            if (gettype($logo) != 'string') {
                $i = $logo->store('images/homepage', 'public');
                $this->attributes['logo'] = $logo->hashName();
            } else {
                $this->attributes['logo'] = $logo;
            }
        }
    }

    public function getLogoAttribute($logo)
    {
        if (is_null($logo) || $logo == '') {
            return asset('assets/media/logo.svg');
        }
        return asset('storage/images/homepage') . '/' . $logo;
    }

    public function setLogoFooterAttribute($logo_footer)
    {
        if (!is_null($logo_footer)) {
            if (gettype($logo_footer) != 'string') {
                $i = $logo->store('images/homepage', 'public');
                $this->attributes['logo_footer'] = $logo_footer->hashName();
            } else {
                $this->attributes['logo_footer'] = $logo_footer;
            }
        }
    }

    public function getLogoFooterAttribute($logo_footer)
    {
        if (is_null($logo_footer) || $logo_footer == '') {
            return asset('assets/media/logo.svg');
        }
        return asset('storage/images/homepage') . '/' . $logo_footer;
    }

    public function setBanar1Attribute($banar1)
    {
        if (!is_null($banar1)) {
            if (gettype($banar1) != 'string') {
                $i = $banar1->store('images/homepage', 'public');
              

                $this->attributes['banar1'] = $banar1->hashName();
               

            } else {
                $this->attributes['banar1'] = $banar1;
            }
        }
    }

    public function getBanar1Attribute($banar1)
    {
        if (is_null($banar1)) {
            return asset('assets/media/slider.png');
        }
        return asset('storage/images/homepage') . '/' . $banar1;
    }

    public function setBanar2Attribute($banar2)
    {
        if (!is_null($banar2)) {
            if (gettype($banar2) != 'string') {
                $i = $banar2->store('images/homepage', 'public');
                

                $this->attributes['banar2'] = $banar2->hashName();
               

            } else {
                $this->attributes['banar2'] = $banar2;
            }
        }
    }

    public function getBanar2Attribute($banar2)
    {
        if (is_null($banar2)) {
            return asset('assets/media/slider.png');
        }
        return asset('storage/images/homepage') . '/' . $banar2;
    }
    public function setBanar3Attribute($banar3)
    {
        if (!is_null($banar3)) {
            if (gettype($banar3) != 'string') {
                $i = $banar3->store('images/homepage', 'public');
               

                $this->attributes['banar3'] = $banar3->hashName();
              

            } else {
                $this->attributes['banar3'] = $banar3;
            }
        }
    }

    public function getBanar3Attribute($banar3)
    {
        if (is_null($banar3)) {
            return asset('assets/media/slider.png');
        }
        return asset('storage/images/homepage') . '/' . $banar3;
    }
    public function setSlider1Attribute($slider1)
    {
        if (!is_null($slider1)) {
            if (gettype($slider1) != 'string') {
                $i = $slider1->store('images/homepage', 'public');
              
                $this->attributes['slider1'] = $slider1->hashName();
           

            } else {
                $this->attributes['slider1'] = $slider1;
            }
        }
    }

    public function getSlider1Attribute($slider1)
    {
        if (is_null($slider1)) {
            return asset('assets/media/slider.png');
        }
        return asset('storage/images/homepage') . '/' . $slider1;
    }
    public function setSlider2Attribute($slider2)
    {
        if (!is_null($slider2)) {
            if (gettype($slider2) != 'string') {
                $i = $slider2->store('images/homepage', 'public');
                ;

                $this->attributes['slider2'] = $slider2->hashName();
              

            } else {
                $this->attributes['slider2'] = $slider2;
            }
        }
    }

    public function getSlider2Attribute($slider2)
    {
        if (is_null($slider2)) {
            return asset('assets/media/slider.png');
        }
        return asset('storage/images/homepage') . '/' . $slider2;
    }
    public function setSlider3Attribute($slider3)
    {
        if (!is_null($slider3)) {
            if (gettype($slider3) != 'string') {
                $i = $slider3->store('images/homepage', 'public');
            

                $this->attributes['slider3'] = $slider3->hashName();
               

            } else {
                $this->attributes['slider3'] = $slider3;
            }
        }
    }

    public function getSlider3Attribute($slider3)
    {
        if (is_null($slider3)) {
            return asset('assets/media/slider.png');
        }
        return asset('storage/images/homepage') . '/' . $slider3;
    }
}
