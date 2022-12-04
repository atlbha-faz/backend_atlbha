<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent_id','icon','number','store_id','status','is_deleted'];
    public function subcategory()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

     public function store()
    {
        return $this->belongsTo(Store::class, 'store_id','id');
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
}
