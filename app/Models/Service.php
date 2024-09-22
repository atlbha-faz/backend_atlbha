<?php

namespace App\Models;

use App\Models\Websiteorder;
use App\Models\Service_Websiteorder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
      protected $fillable = ['name','description','file','price','status','is_deleted'];

    public function pendingServices($id){
       $Websiteorders= Websiteorder::Where('type','service')->where('payment_status','paid')->pluck('id')->toArray();
        $pendingServices=Service_Websiteorder::select('*')->where('service_id',$id)->whereIn('websiteorder_id',$Websiteorders)->where('status','pending')->count();
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
            $this->attributes['file'] = null;
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
