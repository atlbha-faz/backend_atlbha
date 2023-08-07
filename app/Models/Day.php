<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function stores()
    {
        return $this->belongsToMany(
            Store::class,
            'days_stores',
            'day_id',
            'store_id'

        );
    }

}
