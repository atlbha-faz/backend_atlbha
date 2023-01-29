<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    protected $fillable = ['subject','type','message','status','store_id','start_at','end_at','is_deleted'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
