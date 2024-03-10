<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = ['bankId','name','name_ar'];
    protected $casts = [
         'bankId'=>'integer',
    ];
}
