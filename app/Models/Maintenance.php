<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $fillable = ['title','message','store_id','status','is_deleted'];
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}