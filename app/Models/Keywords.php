<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keywords extends Model
{
    use HasFactory;
    protected $fillable = ['index_page_title','index_page_description','key_words','show_pages','link','robots','store_id','status','is_deleted'];
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}