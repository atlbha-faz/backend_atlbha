<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyfatoorahLog extends Model
{
    use HasFactory;
    protected $table="myfatoorah_logs";
    protected $fillable = ['request'];
}
