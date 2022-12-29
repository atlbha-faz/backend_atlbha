<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
 protected $fillable = ['name','status','country_id','is_deleted'];
  public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
