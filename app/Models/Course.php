<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','duration','tags','user_id','status',
    'is_deleted'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function unit()
    {
        return $this->hasMany(Unit::class);
    }
      public function countVideo($course_id)
    {
       $unitid=Unit::select('id')->where('course_id',$course_id)->get();

    //    $unitid=count($unitid);
       //$video=Unit::select('id')->where('id',$course_id)->get()
    $videoes = Video::whereIn('unit_id',$unitid)->get();
    $videoes = count($videoes);
    return  $videoes;
  }

}
