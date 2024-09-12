<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Package extends Model
{
    use HasFactory;
    protected $table="courses_packages";
    protected $fillable = ['package_id','course_id'];
    public $timestamps = false;

}