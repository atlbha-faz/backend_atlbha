<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;

class Page extends Model implements Viewable
{
    use HasFactory;
     use InteractsWithViews;
    protected $fillable = ['title','page_content','altImage','default_page','page_desc','seo_title','seo_link','seo_desc','tags','user_id','status','image','postcategory_id','store_id','is_deleted'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
      public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function page_categories()
    {
       return $this->belongsToMany(
        Page_category::class,
            'pages_page_categories',
            'page_id',
            'page_category_id'
            );
    }

    public function postcategory()
    {
        return $this->belongsTo(Postcategory::class,'postcategory_id','id');
    }
    public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/posts', 'public');
                $this->attributes['image'] = $image->hashName();
            } else {
                $this->attributes['image'] = $image;
            }
        }
    }

    public function getImageAttribute($image)
    {
        if (is_null($image)) {
            return  null;
        }
        return asset('storage/images/posts') . '/' . $image;
    }


}
