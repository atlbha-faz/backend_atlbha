<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class viewplatform extends Model
{
    use HasFactory;
    protected $fillable = ['key','value','status','is_deleted'];

}