<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
         protected $fillable = ['name','status','is_deleted'];


 public function stores()
    {
         return $this->belongsToMany(
         Store::class,
        'activities_stores',
        'activity_id',
        'store_id'
        );
  }
}

