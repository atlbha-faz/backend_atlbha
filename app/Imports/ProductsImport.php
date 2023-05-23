<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row['4']);
            //  dd(Category::where('name',)->pluck('id')->first());
        $parent=Category::where('name',$row['3'])->pluck('id')->first();
        // dd(Category::where('name',$row['6'])->where('parent_id',$parent)->pluck('id')->toArray());

        $sub_categories = explode(',',$row['6']);
        return new Product([

           'name' => $row['0'],
            'for' => 'store',
            'description' => $row['1'],
            'selling_price' => $row['2'],

             'category_id' =>Category::where('name',$row['3'])->pluck('id')->first(),
            'cover' => public_path('images/'.$row['9']),
            'SEOdescription'=> $row['8'],
           'discount_price'=>$row['4'],
           'subcategory_id' => implode(',',Category::whereIn('name',$sub_categories)->where('parent_id',$parent)->pluck('id')->toArray()),
            'discount_percent'=>$row['5'],

             'stock' => $row['7'],





            'store_id'=> auth()->user()->store_id,
        ]);
    }
}
