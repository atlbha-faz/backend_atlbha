<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;
    protected $fillable = ['primaryBg', 'secondaryBg', 'headerBg', 'layoutBg', 'iconsBg', 'productBorder', 'productBg', 'filtersBorder', 'filtersBg', 'mainButtonBg', 'mainButtonBorder', 'subButtonBg', 'subButtonBorder', 'footerBorder', 'footerBg', 'store_id'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
