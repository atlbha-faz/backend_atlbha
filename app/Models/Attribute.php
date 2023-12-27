<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name','type'];
    public function values()
    {
        return $this->hasMany(Value::class);
    }

    public function products()
    {
       return $this->belongsToMany(
        Product::class,
            'attributes_products',
            'attribute_id',
            'product_id'
            )->withPivot("value");
    }
}
