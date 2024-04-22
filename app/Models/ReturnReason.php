<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnReason extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="return_reasons";
    protected $fillable = ['title'];
    public function returnOrder()
    {
        return $this->hasMany(ReturnOrder::class);
    }

}
