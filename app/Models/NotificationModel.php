<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationModel extends Model
{
    use HasFactory;
    protected $table="notifications";
    protected $fillable = ['id',
    'type',
    'notifiable_type','read_at',
    'store_id',
    'user_id',
    'data',
    'created_at',
    'updated_at'
];
protected $casts = [
    'data' => 'array',
];
// public function usetInfo($id)
// {
//    $user=User::where('id',$id)->get();
//    return $user;
// }
public function user()
{
    return $this->belongsTo(User::class);
}
}
