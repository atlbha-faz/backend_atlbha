<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replaycontact extends Model
{
    use HasFactory;
    protected $fillable = ['subject','message','status','contact_id','is_deleted'];
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}