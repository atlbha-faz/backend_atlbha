<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $fillable = ['title','page_content','seo_title','user_id','seo_link','seo_desc','tags','status','is_deleted'];
    public function store()
    {
        return $this->belongsTo(Store::class);
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
   
}