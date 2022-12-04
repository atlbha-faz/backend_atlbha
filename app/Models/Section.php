<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = ['name','cat_id','status','is_deleted'];

    public function category()
    {
        return $this->belongsTo(Category::class,'cat_id', 'id');
    }
}
