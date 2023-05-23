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
        $parent=Category::where('name',$row['category_name'])->pluck('id')->first();
        // dd(Category::where('name',$row['7'])->where('parent_id',$parent)->pluck('id')->toArray());
        
        $sub_categories = explode(',',$row['subcategories']);
        return new Product([

           'name' => $row['name'],
            'for' => 'store',
            'description' => $row['description'],
            'selling_price' => $row['selling_price'],

             'category_id' => Category::where('name',$row['category_name'])->pluck('id')->first(),
            // 'cover' => $row['4'],
            'SEOdescription'=> $row['seodescription'],
           'discount_price'=>$row['discount_price'],
           'subcategory_id' => implode(',',Category::whereIn('name',$sub_categories)->where('parent_id',$parent)->pluck('id')->toArray()),
            'discount_percent'=>$row['discount_percent'],

             'stock' => $row['stock'],



            'store_id'=> auth()->user()->store_id,
        ]);
    }
}
