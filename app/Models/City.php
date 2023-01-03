<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name','name_en','code','status','region_id','is_deleted'];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
