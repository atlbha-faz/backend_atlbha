<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'file', 'course_id', 'status', 'is_deleted'];
    protected $casts = [
        'file' => 'array',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function video()
    {
        return $this->hasMany(Video::class);
    }

    //    public function setFileAttribute($file)
    // {
    //     if (!is_null($file)) {
    //         if (gettype($file) != 'string') {
    //             $i = $file->store('files/unitfile', 'public');
    //             $this->attributes['file'] = $file->hashName();
    //         } else {
    //             $this->attributes['file'] = $file;
    //         }
    //     }
    // }

    public function getFileAttribute($file)
    {
        if (is_null($file)) {
            return array();
        }
        $files = explode(',', $file);
        $url = array();
        foreach ($files as $file) {
            $url[] = asset('storage/files/unitfile') . '/' . str_replace(array('\'', '"', "", ' '), '', $file);
        }
        return $url;

    }

    public function countVideo($unitid)
    {
        $unitid = Video::select('id')->where('unit_id', $unitid)->where('is_deleted', 0)->get();

        $videoes = $unitid->count();
        return $videoes;
    }
    public function durationUnit($unit_id)
    {
        $sum = strtotime('00:00:00');

        $totaltime = 0;
        $videoid = Video::select('id')->where('unit_id', $unit_id)->where('is_deleted', 0)->get();

        //    $unitid=count($unitid);
        //$video=Unit::select('id')->where('id',$course_id)->get()
        $videoes = Video::whereIn('id', $videoid)->get();
        foreach ($videoes as $video) {
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

    }
}
