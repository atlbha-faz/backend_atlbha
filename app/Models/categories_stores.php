<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories_stores extends Model
{
    use HasFactory;
    
    protected $table="categories_stores";
    protected $fillable = ['category_id','subcategory_id','store_id'];

}
