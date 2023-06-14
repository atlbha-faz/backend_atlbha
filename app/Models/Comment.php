<?php

namespace App\Models;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['comment_text','rateing','user_id','comment_for','product_id','status','is_deleted'];

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
     public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
