<?php

namespace App\Models;

use App\Http\Resources\PlanResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;
      protected $fillable = ['name','monthly_price','yearly_price','discount','status','is_deleted'];

      public function stores()
    {
          return $this->belongsToMany(
          Store::class,
          'packages_stores',
          'package_id',
          'store_id'
     );
    }
    
      public function plans()
{
    return $this->belongsToMany(
        Plan::class,
        'packages_plans',
        'package_id',
        'plan_id'
        );
    }
     public function templates()
{
    return $this->belongsToMany(
        Template::class,
        'packages_templates',
        'package_id',
        'template_id'
        );
    }
   
   

}