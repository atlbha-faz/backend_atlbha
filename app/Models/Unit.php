<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = ['title','file','course_id','status','is_deleted'];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function video()
    {
        return $this->hasMany(video::class);
    }

       public function setFileAttribute($file)
    {
        if (!is_null($file)) {
            if (gettype($file) != 'string') {
                $i = $file->store('files/unitfile', 'public');
                $this->attributes['file'] = $file->hashName();
            } else {
                $this->attributes['file'] = $file;
            }
        }
    }

    public function getFileAttribute($file)
    {
        if (is_null($file)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/files/unitfile') . '/' . $file;
    }

      public function countVideo($unitid)
    {
        $unitid=Video::select('id')->where('unit_id',$unitid)->get();

$videoes=$unitid->count();
    return  $videoes;
  }
}