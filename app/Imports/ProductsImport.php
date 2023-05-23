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
        $parent=Category::where('name',$row['4'])->pluck('id')->first();
        // dd(Category::where('name',$row['7'])->where('parent_id',$parent)->pluck('id')->toArray());
        
        $sub_categories = explode(',',$row['7']);
        return new Product([

           'name' => $row['1'],
            'for' => 'store',
            'description' => $row['2'],
            'selling_price' => $row['3'],

             'category_id' =>Category::where('name',$row['4'])->pluck('id')->first(),
            // 'cover' => $row['4'],
            'SEOdescription'=> $row['9'],
           'discount_price'=>$row['5'],
           'subcategory_id' => implode(',',Category::whereIn('name',$sub_categories)->where('parent_id',$parent)->pluck('id')->toArray()),
            'discount_percent'=>$row['6'],

             'stock' => $row['8'],



            'store_id'=> auth()->user()->store_id,
        ]);
    }
}
