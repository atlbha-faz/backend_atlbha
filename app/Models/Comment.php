<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['comment_text','rateing','user_id','product_id','status','is_deleted'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
      public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function replaycomment()
    {
        return $this->hasMany(Replaycomment::class);
    }
}
