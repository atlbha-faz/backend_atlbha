<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','is_deleted'];

    public function packages()
    {
     return $this->belongsToMany(
        Package::class,
        'packages_plans',
        'plan_id',
        'package_id'
        );
    }
}
