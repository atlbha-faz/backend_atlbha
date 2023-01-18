<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','duration','tags','image','link','user_id','status',
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
       public function durationCourse($course_id)
    {
        $sum = strtotime('00:00:00');

     $totaltime = 0;
       $unitid=Unit::select('id')->where('course_id',$course_id)->get();

    //    $unitid=count($unitid);
       //$video=Unit::select('id')->where('id',$course_id)->get()
    $videoes = Video::whereIn('unit_id',$unitid)->get();
    foreach($videoes as $video){
           // Converting the time into seconds
    $timeinsec = strtotime($video->duration) - $sum;

    // Sum the time with previous value
    $totaltime = $totaltime + $timeinsec;
}

// Totaltime is the summation of all
// time in seconds

// Hours is obtained by dividing
// totaltime with 3600
$h = intval($totaltime / 3600);

$totaltime = $totaltime - ($h * 3600);

// Minutes is obtained by dividing
// remaining total time with 60
$m = intval($totaltime / 60);

// Remaining value is seconds
$s = $totaltime - ($m * 60);

// Printing the result
return ("$h:$m:$s");



    return  $$durationVideo;
  }

public function setImageAttribute($image)
    {
        if (!is_null($image)) {
            if (gettype($image) != 'string') {
                $i = $image->store('images/courses', 'public');
                $this->attributes['image'] = $image->hashName();
            } else {
                $this->attributes['image'] = $image;
            }
        }
    }

    public function getImageAttribute($image)
    {
        if (is_null($image)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/courses') . '/' . $image;
    }
    
}
