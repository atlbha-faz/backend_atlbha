<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\Product;
use App\Models\Package_store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;
     protected $fillable = ['store_name','store_email','domain','icon','description','business_license','phonenumber','commercialregistertype','confirmation_status','confirmation_date','link','verification_status',
     'snapchat','facebook','twiter','youtube','instegram','logo','entity_type','user_id','activity_id','package_id','country_id','city_id','user_country_id','user_city_id','category_id','start_at','end_at','period',
     'periodtype','special','file','tiktok','status','is_deleted'];

     public function rate($id){
        $product_id=Product::select('id')->where('store_id',$id)->get();
        return Comment::whereIn('product_id',$product_id)->where('comment_for','store')->avg('rateing');
     }
     protected $casts = [
        'activity_id' => 'array',
    ];

     public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function importproduct(){
              return $this->hasMany(Importproduct::class);
         }

     public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
          public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');

    }
        public function usercity()
    {
        return $this->belongsTo(City::class, 'user_city_id', 'id');
    }
          public function usercountry()
    {
        return $this->belongsTo(Country::class, 'user_country_id', 'id');

    }
    public function activities()
     {
          return $this->belongsToMany(
         Activity::class,
        'activities_stores',
        'store_id',
        'activity_id'

        );
    }
     public function packages()
    {
          return $this->belongsToMany(
          Store::class,
          'packages_stores',
          'store_id',
          'package_id'

     );
    }
    public function left($id){
       $day=Store::select('end_at')->where('id',$id)->first();
        $date1 = new DateTime($day->end_at);
        $now_date= Carbon::now();
        $interval = $date1->diff($now_date);
        return $interval->days;
    }
    public function period($id){
        $period=Store::select('periodtype')->where('id',$id)->first();
         return $period->period;
     }
     public function packagee($id){
        if (is_null($id))
        return "no_subscription";
        $package=Package::select('name')->where('id',$id)->first();
         return $package->name;
     }
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function page()
    {
        return $this->hasMany(Page::class);
    }
      public function shippingtypes()
    {
     return $this->belongsToMany(
        Shippingtype::class,
        'shippingtypes_stores',
        'store_id',
        'shippingtype_id'


        );
    }
    public function paymenttypes()
    {
     return $this->belongsToMany(
        Paymenttype::class,
        'paymenttypes_stores',
        'store_id',
        'paymenttype_id'
        );
    }

       public function setLogoAttribute($logo)
    {
        if (!is_null($logo)) {
            if (gettype($logo) != 'string') {
                $i = $logo->store('images/storelogo', 'public');
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
        return asset('storage/images/storelogo') . '/' . $logo;
    }


       public function setIconAttribute($icon)
    {
        if (!is_null($icon)) {
            if (gettype($icon) != 'string') {
                $i = $icon->store('images/storeicon', 'public');
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
        return asset('storage/images/storeicon') . '/' . $icon;
    }


       public function setFileAttribute($file)
    {
        if (!is_null($file)) {
            if (gettype($file) != 'string') {
                $i = $file->store('files/store', 'public');
                $this->attributes['file'] = $file->hashName();
            } else {
                $this->attributes['file'] = $file;
            }
        }
    }

    public function getFileAttribute($file)
    {
        if (is_null($file)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/files/store') . '/' . $file;
    }


       public function setBusinesslicenseAttribute($business_license)
    {
        if (!is_null($business_license)) {
            if (gettype($business_license) != 'string') {
                $i = $business_license->store('images/storebusiness_license', 'public');
                $this->attributes['business_license'] = $business_license->hashName();
            } else {
                $this->attributes['business_license'] = $business_license;
            }
        }
    }

    public function getBusinesslicenseAttribute($business_license)
    {
        if (is_null($business_license)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/storebusiness_license') . '/' . $business_license;
    }

      public function note()
    {
        return $this->hasMany(Note::class);
    }

}
