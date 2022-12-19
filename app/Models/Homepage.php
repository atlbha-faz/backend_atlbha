<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    use HasFactory;
    protected $fillable = ['logo','panar1','panarstatus1','panar2','panarstatus2','panar3','panarstatus3','clientstatus','commentstatus',
    'slider1','sliderstatus1','slider2','sliderstatus2','slider3','sliderstatus3','store_id','is_deleted'];
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
        if (is_null($logo)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/homepage') . '/' . $logo;
    }
     public function setPanar1Attribute($panar1)
    {
        if (!is_null($panar1)) {
            if (gettype($panar1) != 'string') {
                $i = $panar1->store('images/homepage', 'public');
                $this->attributes['panar1'] = $panar1->hashName();
            } else {
                $this->attributes['panar1'] = $panar1;
            }
        }
    }

    public function getPanar1Attribute($panar1)
    {
        if (is_null($panar1)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/homepage') . '/' . $panar1;
    }
      public function setPanar2Attribute($panar2)
    {
        if (!is_null($panar2)) {
            if (gettype($panar2) != 'string') {
                $i = $panar2->store('images/homepage', 'public');
                $this->attributes['panar2'] = $panar2->hashName();
            } else {
                $this->attributes['panar2'] = $panar2;
            }
        }
    }

    public function getPanar2Attribute($panar2)
    {
        if (is_null($panar2)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/homepage') . '/' . $panar2;
    }
       public function setPanar3Attribute($panar3)
    {
        if (!is_null($panar3)) {
            if (gettype($panar3) != 'string') {
                $i = $panar3->store('images/homepage', 'public');
                $this->attributes['panar3'] = $panar3->hashName();
            } else {
                $this->attributes['panar3'] = $panar3;
            }
        }
    }

    public function getPanar3Attribute($panar3)
    {
        if (is_null($panar3)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/homepage') . '/' . $panar3;
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
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/homepage') . '/' . $slider1;
    }
        public function setSlider2Attribute($slider2)
    {
        if (!is_null($slider2)) {
            if (gettype($slider2) != 'string') {
                $i = $slider2->store('images/homepage', 'public');
                $this->attributes['slider2'] = $slider2->hashName();
            } else {
                $this->attributes['slider2'] = $slider2;
            }
        }
    }

    public function getSlider2Attribute($slider2)
    {
        if (is_null($slider2)) {
            return   asset('assets/media/man.png');
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
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/homepage') . '/' . $slider3;
    }
<<<<<<< HEAD

}
=======
}

>>>>>>> 3a0d838cf6591f78fe755ff6858d0dc85fc2d24b
