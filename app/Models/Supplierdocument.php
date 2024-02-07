<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplierdocument extends Model
{
    use HasFactory;
    protected $fillable = ['file','type','desc','supplierCode','store_id'];
    
    public function setFileAttribute($file)
    {
        if (!is_null($file)) {
            if (gettype($file) != 'string') {
                $i = $file->store('images/storelogo', 'public');
                $this->attributes['file'] = $file->hashName();
            } else {
                $this->attributes['file'] = $file;
            }
        }
    }
    public function getFileAttribute($file)
    {
        if (is_null($file)) {
            return asset('assets/media/man.png');
        }
        return asset('storage/images/storelogo') . '/' . $file;
    }
}
