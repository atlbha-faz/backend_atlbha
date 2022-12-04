<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['subject','message','status','store_id','is_deleted'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}