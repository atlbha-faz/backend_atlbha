<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $fillable = ['title','page_content','seo_title','seo_link','seo_desc','tags','user_id','status','image','postcategory_id','store_id','is_deleted'];
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
        return $this->belongsTo(Postcategory::class);
    }

}