<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalSupport extends Model
{
    use HasFactory;
        protected $fillable = ['title','phonenumber','content','supportstatus','type','store_id','user_id','status','is_deleted'];

          public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');

    }
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
