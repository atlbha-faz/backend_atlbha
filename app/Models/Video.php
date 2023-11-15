<?php

namespace App\Models;

use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['video', 'name', 'duration', 'unit_id', 'status', 'is_deleted'];
    protected $casts = [
        'video' => 'array',
    ];
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

    // public function getVideoAttribute($video)
    // {
    //     if (is_null($video)) {
    //         return   asset('assets/media/man.png');
    //     }
    //     return asset('storage/videos') . '/' . str_replace( array( '\'', '"', "" , ';', '<', '>' ), '', $video);
    // }

    public function getImageAttribute($image)
    {
        if (is_null($image)) {
            return asset('assets/media/man.png');
        }
        return asset('storage/images/courses') . '/' . $image;
    }
    // get video details using youtube api
    public function get_youtube_title($video_id)
    {
        $html = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . '&key=AIzaSyD1TiI8Zisi_r9prIjGtSWen46na2HUpjA&part=snippet,contentDetails';
        $response = file_get_contents($html);
        $decoded = json_decode($response, true);
        $data = array();
        foreach ($decoded['items'] as $items) {
            $title = $items['snippet']['title'];
            $duration = $items['contentDetails']['duration'];
            $duration = new DateInterval($duration);
            $newduration = $duration->format('%H:%i:%s'); // outputs: 00:24:30
            $data[] = [
                'title' => $title,
                'duration' => $newduration,
            ];

        }
        return $data;

    }

 

}
