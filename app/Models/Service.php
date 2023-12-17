<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
      protected $fillable = ['name','description','file','price','status','is_deleted'];

    public function pendingServices($id){
        $pendingServices=Service_Websiteorder::select('*')->where('service_id',$id)->where('status','pending')->count();
        return  $pendingServices;
    }
    public function setFileAttribute($file)
    {
        if (!is_null($file)) {
            if (gettype($file) != 'string') {
                $i = $file->store('images/service', 'public');
                $this->attributes['file'] = $file->hashName();
            } else {
                $this->attributes['file'] = $file;
            }
        }
        else {
            $this->attributes['file'] = $file;
        }
    }

    public function getFileAttribute($file)
    {
        if (is_null($file)) {
            return null;
        }
        else{
        return asset('storage/images/service') . '/' . $file;
        }
    }
    public function websiteorders()
    {
       return $this->belongsToMany(
       Websiteorder::class,
          'services_websiteorders',
            'service_id',
            'websiteorder_id'
            );
    }

}
?>
