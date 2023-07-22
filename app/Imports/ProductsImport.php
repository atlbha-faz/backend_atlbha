<?php

namespace App\Imports;

use Throwable;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
// use Maatwebsite\Excel\Concerns\SkipsFailures;
// use Maatwebsite\Excel\Concerns\SkipsFailures;



class ProductsImport implements ToModel,
 WithHeadingRow,
 SkipsOnError,
 WithValidation,
//  SkipsFailures
//  SkipsFailures,
SkipsOnFailure

{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
//dd($row['9']);

// Log::alert($row['cover']);
        // dd($row['4']);
            //  dd(Category::where('name',)->pluck('id')->first());
        $parent=Category::where('name',$row['category_id'])->where('store_id',auth()->user()->store_id)->pluck('id')->first();
        // dd(Category::where('name',$row['6'])->where('parent_id',$parent)->pluck('id')->toArray());

        $sub_categories = explode(',',$row['subcategory_id']);
        return new Product([

           'name' => $row['name'],
            'for' => 'store',
            'description' => $row['description'],
            'selling_price' => $row['selling_price'],

             'category_id' =>Category::where('name',$row['category_id'])->where('store_id',auth()->user()->store_id)->pluck('id')->first(),
            // 'cover' => $row['4'],
            'SEOdescription'=> $row['seo'],
           'discount_price'=>$row['discount_price'],
           'subcategory_id' => implode(',',Category::whereIn('name',$sub_categories)->where('parent_id',$parent)->where('store_id',auth()->user()->store_id)->pluck('id')->toArray()),
            //'discount_percent'=>$row['discount_percent'],

             'stock' => $row['stock'],



            'store_id'=> auth()->user()->store_id,
        ]);




        return $product;


    }

     public function rules(): array {
    return [
            '*.name' => 'required|string',
            '*.description'=>'required|string',
            '*.selling_price'=>['required','numeric','gt:0'],
            '*.stock'=>['required','numeric','gt:0'],
            // 'cover'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            '*.discount_price'=>['nullable','numeric'],
           // '*.discount_percent'=>['required','numeric'],
             '*.seo'=>'nullable',
            '*.category_id'=>'required|exists:categories,name',
            // '*.subcategory_id'=>['array'],
            '*.subcategory_id.*'=>['required','string']
            // Rule::exists('categories', 'id')->where(function ($query) {
            // return $query->join('categories', 'id', 'parent_id');
        // }),



    ];

}
 public function onError(Throwable $e)
    {
        return "validation er";
    }

    public function onFailure(Failure ...$failure)
    {
    }

}
