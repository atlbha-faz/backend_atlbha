<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page_page_category extends Model
{
    protected $table="pages_page_categories";
    protected $fillable = ['page_id','page_category_id'];
    protected $casts = [
        'page_category_id' => 'array',
    ];
    use HasFactory;
}
