<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
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
