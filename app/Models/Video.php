<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['video','duration','unit_id','status','is_deleted'];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    //  public function setVideoAttribute($video)
    // {
    //     if (!is_null($video)) {
    //         if (gettype($video) != 'string') {
    //             $i = $video->store('videos/video', 'public');
    //             $this->attributes['video'] = $video->hashName();
    //         } else {
    //             $this->attributes['video'] = $video;
    //         }
    //     }
    // }

    public function getVideoAttribute($video)
    {
        if (is_null($video)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/videos/video') . '/' . $video;
    }

      public function getVideoDuration($video)
    {
        $getID3 = new \getID3();
        $pathVideo = 'storage/videos/' . $video;
        $fileAnalyze = $getID3->analyze($pathVideo);
        $playtime = $fileAnalyze['playtime_string'];

        return $playtime;
    }

}
