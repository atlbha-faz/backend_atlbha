<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = ['name','parent_id','status','is_deleted'];

     public function subtemplate()
    {
        return $this->hasMany(Template::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Template::class, 'parent_id');
    }

    public function packages()
{
    return $this->belongsToMany(
        Package::class,
        'packages_templates',
        'template_id',
        'package_id'

        );
    }


}