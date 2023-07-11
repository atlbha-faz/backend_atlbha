<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replaycomment extends Model
{
    use HasFactory;
    protected $fillable = ['comment_text','user_id','comment_id','status','is_deleted'];
    
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}