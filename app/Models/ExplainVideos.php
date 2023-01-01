<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExplainVideos extends Model
{
    use HasFactory;
    protected $fillable = ['title','video','thumbnail','duration','user_id','status','is_deleted'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function setVideoAttribute($video)
    {
        if (!is_null($video)) {
            if (gettype($video) != 'string') {
                $i = $video->store('videos/explainvideo', 'public');
                $this->attributes['video'] = $video->hashName();
            } else {
                $this->attributes['video'] = $video;
            }
        }
    }

    public function getVideoAttribute($video)
    {
        if (is_null($video)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/videos/explainvideo') . '/' . $video;
    }
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
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/thumbnail') . '/' . $thumbnail;
    }
}
