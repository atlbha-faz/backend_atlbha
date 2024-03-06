<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['bankId','bankAccountHolderName','bankAccount','iban','supplierCode','store_id','status','comment'];
}
