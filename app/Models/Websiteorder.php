<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Websiteorder extends Model
{
    use HasFactory;
    protected $fillable = ['order_number','type','store_id','status','is_deleted'];

     public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function services()
    {

       return $this->belongsToMany(
        Service::class,
         'services_websiteorders',
            'websiteorder_id',
            'service_id'
            );
    }
}
