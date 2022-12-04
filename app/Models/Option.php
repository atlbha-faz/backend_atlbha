<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = ['key','value','status','is_deleted'];
    public function products()
    {
       return $this->belongsToMany(
        Product::class,
            'options_products',
            'option_id',
            'product_id');
    }
}