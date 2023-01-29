<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postcategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','is_deleted'];
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

}
