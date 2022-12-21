<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_Websiteorder extends Model
{
    use HasFactory;
    protected $table="services_websiteorders";
    protected $fillable = ['service_id','websiteorder_id','status'];
}