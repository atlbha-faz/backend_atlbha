<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package_Template extends Model
{
    use HasFactory;
    protected $table="packages_templates";
    protected $fillable = ['package_id','template_id'];

    public $timestamps = false;

}