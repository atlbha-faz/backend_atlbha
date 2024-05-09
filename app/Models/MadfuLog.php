<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadfuLog extends Model
{
    use HasFactory;

    protected $table = 'madfu_logs';
    protected $fillable = ['request'];
}
