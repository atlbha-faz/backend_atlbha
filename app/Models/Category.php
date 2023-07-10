<?php

namespace App\Models;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','for','parent_id','icon','number','store_id','status','is_deleted'];
    public function subcategory()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_deleted',0);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

     public function store()
    {
        return $this->belongsTo(Store::class, 'store_id','id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function setIconAttribute($icon)
    {
        if (!is_null($icon)) {
            if (gettype($icon) != 'string') {
                $i = $icon->store('images/icon/category', 'public');
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
        return asset('storage/images/icon/category') . '/' . $icon;
    }


     public function offers()
  {
     return $this->belongsToMany(
        Offer::class,
        'categories_offers',
        'category_id',
        'offer_id'
        )->withPivot("type");
  }
       public function coupons()
  {
     return $this->belongsToMany(
        Coupon::class,
        'categories_coupons',
        'category_id',
        'coupon_id'
        );
  }

}
