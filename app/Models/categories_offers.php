<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories_offers extends Model
{
    use HasFactory;
    protected $table="categories_offers";
    protected $fillable = ['offer_id','category_id','type'];
    protected $casts = [
        'category_id' => 'array',
    ];
}
