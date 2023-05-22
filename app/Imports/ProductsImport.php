<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'name' => $row['name'],
            'for' => 'store',
            'description' => $row['description'],
            'selling_price' => $row['selling_price'],
            'stock' => $row['stock'],
            'cover' => $row['cover'],
            'SEOdescription'=> 'dd',
           'discount_price'=>$row['discount_price'],
            'discount_percent'=>$row['discount_percent'],
            'subcategory_id' => $row['subcategory_id'],
            'category_id' => $row['category_id'],
            'store_id'=> auth()->user()->store_id,
          ]);
    }
}
