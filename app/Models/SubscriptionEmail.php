<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionEmail extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'store_id'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
