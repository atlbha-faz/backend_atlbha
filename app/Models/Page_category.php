<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page_category extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','is_deleted'];
    
    public function pages()
{
  
   return $this->belongsToMany(
        Page::class,
        'pages_page_categories',
        'page_category_id',
        'page_id');
}
}