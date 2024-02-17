<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'for', 'parent_id', 'icon', 'number', 'store_id', 'status', 'is_deleted'];
    public function subcategory()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_deleted', 0);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function stores()
    {
        return $this->belongsToMany(
            Store::class,
            'categories_stores',
            'category_id',
            'store_id'
        )->withPivot('subcategory_id');
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
            return asset('assets/media/category_defulte.png');
        } else {
            return asset('storage/images/icon/category') . '/' . $icon;}
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
    public function Possibility_of_delete($id)
    {
        $productsCount = Product::where('category_id', $id)->where('is_deleted', 0)->count();

        if ($productsCount > 0) {
            return false;
        } else {
            return true;

        }

    }
}
