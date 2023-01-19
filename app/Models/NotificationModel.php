<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;
    protected $table="notifications";
    protected $fillable = ['id',
    'type',
    'notifiable_type','read_at',
    'store_id',
    'data',
    'created_at',
    'updated_at'
];
protected $casts = [
    'data' => 'array',
];

}
