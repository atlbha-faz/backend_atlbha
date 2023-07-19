<?php

namespace App\Imports;

use Throwable;
use App\Models\Option;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AdminProductImport implements ToCollection
, WithHeadingRow,
 SkipsOnError,
 WithValidation,
SkipsOnFailure
{

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {

           $parent=Category::where('name',$row['category_id'])->pluck('id')->first();
        // dd(Category::where('name',$row['6'])->where('parent_id',$parent)->pluck('id')->toArray());

        $sub_categories = explode(',',$row['subcategory_id']);
            $product = Product::create([
          'name' => $row['name'],
          'for' => 'stock',
          'quantity' => $row['quantity'],
          'less_qty' =>$row['less_qty'],
          'description' => $row['description'],
          'purchasing_price' =>$row['purchasing_price'],
          'selling_price' => $row['selling_price'],
          'stock' => $row['stock'],
          'cover' => $row['cover'],
          'SEOdescription'=> $row['seo'],
          'category_id' =>Category::where('name',$row['category_id'])->pluck('id')->first(),
           'subcategory_id' => implode(',',Category::whereIn('name',$sub_categories)->where('parent_id',$parent)->pluck('id')->toArray()),
          'store_id' => null,

        ]);
         $productid =$product->id;
        //   $myimage = $row['images'];
        //    $myArray = explode(',', $myimage);
        //      foreach ($myArray as $value) {
        //  Image::create(
        //    ['image'=>$value,
        // 'product_id'=>$productid]);
        //  }
         $arrayValues=array();
               $type = $row['optiontype'];
            $types = explode(',', $type);
             $title = $row['optiontitle'];
            $titles = explode(',', $title);
             $value = $row['optionvalue'];
            $values = explode(',', $value);
              for ($x = 0; $x < count($types); $x++) {
        $arrayValues[$x][]=$values[$x];}
      for ($x = 0; $x < count($types); $x++) {
           $option= Option::create([
                  'type' => $types[$x],
                  'title' => $titles[$x],
                  'value' => implode(',', $arrayValues[$x]),

                  'product_id' =>  $productid

                ]);
            }
        }
    }
       public function rules(): array {
    return [
            '*.name' => 'required|string',
            '*.description'=>'required|string',
            '*.selling_price'=>['required','numeric','gt:0'],
            '*.purchasing_price'=>['required','numeric','gt:0'],
            '*.quantity'=>['required','numeric','gt:0'],
            '*.less_qty'=>['required','numeric','gt:0'],
            '*.stock'=>['required','numeric','gt:0'],
            // 'cover'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            '*.discount_price'=>['required','numeric'],
          //  '*.discount_percent'=>['required','numeric'],
            '*.seo'=>'required',
            '*.category_id'=>'required|exists:categories,name',
            // '*.subcategory_id'=>['array'],
            '*.subcategory_id.*'=>['required','string'],
            // Rule::exists('categories', 'id')->where(function ($query) {
            // return $query->join('categories', 'id', 'parent_id');
        // }),
         '*.optiontype.*'=>'required|in:brand,color,wight,size',
          '*.optiontitle.*'=>'required|string',
          '*.optionvalue.*'=>'required|string',


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
