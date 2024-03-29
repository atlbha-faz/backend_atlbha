<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

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

        foreach ($rows as $row) {
            $minprice = $row['purchasing_price'];

            $parent = Category::where('name', $row['category_id'])->pluck('id')->first();
            // dd(Category::where('name',$row['6'])->where('parent_id',$parent)->pluck('id')->toArray());
            if (isset($row['subcategory_id']) && $row['subcategory_id'] != null) {
                $sub_categories = explode(',', $row['subcategory_id']);
            } else {
                $sub_categories = null;
            }
            if (isset($row['seo']) && $row['seo'] != null) {$seo = $row['seo'];} else {
                $seo = null;
            }
            if (isset($row['snappixel']) && $row['snappixel'] != null) {$snappixel = $row['snappixel'];} else {
                $snappixel = null;
            }
            if (isset($row['tiktokpixel']) && $row['tiktokpixel'] != null) {$tiktokpixel = $row['tiktokpixel'];} else {
                $tiktokpixel = null;
            }
            if (isset($row['twitterpixel']) && $row['twitterpixel'] != null) {$twitterpixel = $row['twitterpixel'];} else {
                $twitterpixel = null;
            }
            if (isset($row['instapixel']) && $row['instapixel'] != null) {$instapixel = $row['instapixel'];} else {
                $instapixel = null;
            }

            if (isset($row['robot_link']) && $row['robot_link'] != null) {$robot_link = $row['robot_link'];} else {
                $robot_link = null;
            }
            if (isset($row['google_analytics']) && $row['google_analytics'] != null) {$google_analytics = $row['google_analytics'];} else {
                $google_analytics = null;
            }
            if (isset($row['weight']) && $row['weight'] != null) {$weight = $row['weight'];} else {
                $weight = null;
            }

            $product = Product::create([
                'name' => $row['name'],
                'for' => 'stock',
                //   'quantity' => $row['quantity'],
                //   'less_qty' =>$row['less_qty'],
                'description' => $row['description'],
                'purchasing_price' => $row['purchasing_price'],
                'selling_price' => $row['selling_price'],
                'stock' => $row['stock'],
                'cover' => $row['cover'],
                'SEOdescription' => $seo,
                'category_id' => Category::where('name', $row['category_id'])->pluck('id')->first(),
                'subcategory_id' => $sub_categories == null ? null : implode(',', Category::whereIn('name', $sub_categories)->where('parent_id', $parent)->pluck('id')->toArray()),
                'store_id' => null,
                'short_description' => $row['short_description'],
                'snappixel' => $snappixel,
                'tiktokpixel' => $tiktokpixel,
                'twitterpixel' => $twitterpixel,
                'instapixel' => $instapixel,
                'robot_link' => $robot_link,
                'google_analytics' => $google_analytics,
                'weight' => $weight,

            ]);
            $productid = $product->id;
            //   $myimage = $row['images'];
            //    $myArray = explode(',', $myimage);
            //      foreach ($myArray as $value) {
            //  Image::create(
            //    ['image'=>$value,
            // 'product_id'=>$productid]);
            //  }
            //      $arrayValues=array();
            //            $type = $row['optiontype'];
            //         $types = explode(',', $type);
            //          $title = $row['optiontitle'];
            //         $titles = explode(',', $title);
            //          $value = $row['optionvalue'];
            //         $values = explode(',', $value);
            //           for ($x = 0; $x < count($types); $x++) {
            //     $arrayValues[$x][]=$values[$x];}
            //   for ($x = 0; $x < count($types); $x++) {
            //        $option= Option::create([
            //               'type' => $types[$x],
            //               'title' => $titles[$x],
            //               'value' => implode(',', $arrayValues[$x]),

            //               'product_id' =>  $productid

            //             ]);
            //         }
        }
    }
    public function rules(): array
    {
        return [
            '*.name' => 'required|string',
            '*.description' => 'required|string',
            '*.purchasing_price' => ['required', 'numeric', 'gt:0'],
            '*.selling_price' => ['required', 'numeric'],
            '*.short_description' => 'required|string|max:100',
            // '*.quantity'=>['required','numeric','gt:0'],
            // '*.less_qty'=>['required','numeric','gt:0'],
            '*.stock' => ['required', 'numeric', 'gt:0'],
            // 'cover'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
            '*.discount_price' => ['nullable', 'numeric'],
            //  '*.discount_percent'=>['required','numeric'],
            '*.seo' => 'nullable',
            '*.snappixel' => 'nullable',
            '*.tiktokpixel' => 'nullable',
            '*.twitterpixel' => 'nullable',
            '*.instapixel' => 'nullable',
            '*.robot_link' => 'nullable',
            '*.google_analytics' => 'nullable',
            '*.weight' => 'nullable',
            '*.category_id' => 'required|exists:categories,name',
            // '*.subcategory_id'=>['array'],
            '*.subcategory_id.*' => ['nullable', 'string'],
            // Rule::exists('categories', 'id')->where(function ($query) {
            // return $query->join('categories', 'id', 'parent_id');
            // }),
            //  '*.optiontype.*'=>'required|in:brand,color,wight,size',
            //   '*.optiontitle.*'=>'required|string',
            //   '*.optionvalue.*'=>'required|string',

        ];

    }
    public function onError(Throwable $e)
    {
        return "validation er";
    }

    public function onFailure(Failure...$failure)
    {
    }
}
