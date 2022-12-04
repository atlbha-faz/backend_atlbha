<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','notificationtype_id','description','notification_time','status','is_deleted'];



    public function notification_type()
    {
        return $this->belongsTo(Notification_type::class,'notificationtype_id','id');
    }

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}