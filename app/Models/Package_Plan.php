<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package_Plan extends Model
{
    use HasFactory;
    protected $table="packages_plans";
    protected $fillable = ['package_id','plan_id'];

    public $timestamps = false;

}