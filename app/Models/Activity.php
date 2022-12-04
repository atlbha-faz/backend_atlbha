<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
         protected $fillable = ['name','status','is_deleted'];


 public function store()
    {
        return $this->hasMany(Store::class);
    }
}
