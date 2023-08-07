<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day_Store extends Model
{
    use HasFactory;
    protected $table="days_stores";
    protected $fillable = ['day_id','store_id','from','to','status'];
    public function day()
    {
        return $this->belongsTo(Day::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
