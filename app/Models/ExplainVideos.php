<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateInterval;

class ExplainVideos extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'video', 'thumbnail', 'duration', 'user_id', 'status', 'is_deleted'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //  public function setVideoAttribute($video)
    // {
    //     if (!is_null($video)) {
    //         if (gettype($video) != 'string') {
    //             $i = $video->store('videos/explainvideo', 'public');
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
    //     return asset('storage/videos/explainvideo') . '/' . $video;
    // }
    public function seTthumbnailAttribute($thumbnail)
    {
        if (!is_null($thumbnail)) {
            if (gettype($thumbnail) != 'string') {
                $i = $thumbnail->store('images/thumbnail', 'public');
                $this->attributes['thumbnail'] = $thumbnail->hashName();
            } else {
                $this->attributes['thumbnail'] = $thumbnail;
            }
        }
    }

    public function getThumbnailAttribute($thumbnail)
    {
        if (is_null($thumbnail)) {
            return asset('assets/media/man.png');
        }
        return asset('storage/images/thumbnail') . '/' . $thumbnail;
    }

    
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
