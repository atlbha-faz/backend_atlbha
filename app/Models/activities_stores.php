<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activities_stores extends Model
{
    use HasFactory;
      protected $table="activities_stores";
    protected $fillable = ['activity_id','store_id'];
}
