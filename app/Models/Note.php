<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
      protected $fillable = ['subject','details','store_id','product_id'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
