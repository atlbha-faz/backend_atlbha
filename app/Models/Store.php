<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use App\Models\Comment;
use App\Models\Package;
use App\Models\Product;
use App\Models\Category;
use App\Models\Maintenance;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;
     protected $fillable = ['store_name','store_email','domain','slug','icon','description','business_license','phonenumber','commercialregistertype','link','verification_status','store_address',
     'snapchat','facebook','twiter','youtube','instegram','logo','entity_type','user_id','activity_id','package_id','country_id','city_id','user_country_id','user_city_id','category_id','start_at','end_at','period','verification_date',
     'periodtype','special','file','tiktok','working_status','status','category_id','subcategory_id','is_deleted'];

    public function rate($id)
    {
        $product_id = Product::select('id')->where('store_id', $id)->get();
        return Comment::whereIn('product_id', $product_id)->where('comment_for', 'store')->avg('rateing');
    }
    protected $casts = [
        'activity_id' => 'array',
    ];
    public function setDomainAttribute($value)
    {
        $this->attributes['domain'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    public function theme()
    {
    return $this->hasOne(Theme::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function importproduct()
    {
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
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'categories_stores',
            'store_id',
            'category_id')
            ->withPivot('subcategory_id');
    }
//     public function category()
//     {
//         return $this->belongsTo(Category::class,'category_id','id');
//     }
//   public function subcategory()
//     {
//         return Category::whereIn('id',explode(',',$this->subcategory_id))->get();
//     }
    public function packages()
    {
        return $this->belongsToMany(
            Package::class,
            'packages_stores',
            'store_id',
            'package_id'

        );
    }
    public function days()
    {
        return $this->belongsToMany(
            Day::class,
            'days_stores',
            'store_id',
            'day_id'

        );
    }
    public function daystore()
    {
        return $this->hasMany(
            Day_Store::class

        );
    }

    public function left($id)
    {  $store= Store::where('id', $id)->first();
        if($store->package_id == null){
            return 0;  
        }
        else{
        $day = Store::select('end_at')->where('id', $id)->first();
        $date1 = new DateTime($day->end_at);
        $now_date = Carbon::now();
        $interval = $date1->diff($now_date);
        return $interval->days;
        }
    }
    public function period($id)
    {
        $period = Store::select('periodtype')->where('id', $id)->first();
        return $period->period;
    }
    public function packagee($id)
    {
        if (is_null($id)) {
            return "no_subscription";
        }

        $package = Package::select('name')->where('id', $id)->first();
        return $package->name;
    }
    public function packagestatus($id)
    {
        $store= Store::where('id', $id)->first();
        $store_package=Package_store::where('package_id',$store->package_id)->where('store_id',$store->id)->orderBy('id', 'DESC')->first();
        if($store_package !=null){
        return    $store_package->status;
        }
        else{
            return null; 
        }
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
        )->withPivot('price');
    }
    public function paymenttypes()
    {
        return $this->belongsToMany(
            Paymenttype::class,
            'paymenttypes_stores',
            'store_id',
            'paymentype_id'
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
            return asset('assets/media/logo.svg');
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
            return asset('assets/media/logo.svg');
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
            return asset('assets/media/man.png');
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
            return asset('assets/media/man.png');
        }
        return asset('storage/images/storebusiness_license') . '/' . $business_license;
    }

    public function note()
    {
        return $this->hasMany(Note::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
      public function maintenance()
    {
        return $this->hasOne(Maintenance::class);
    }

}
