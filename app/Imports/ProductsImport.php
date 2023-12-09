<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

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
        $parent = Category::where('name', $row['category_id'])->where('store_id', auth()->user()->store_id)->pluck('id')->first();
        // dd(Category::where('name',$row['6'])->where('parent_id',$parent)->pluck('id')->toArray());

        if (isset($row['subcategory_id']) && $row['subcategory_id'] != null) {
            $sub_categories = explode(',', $row['subcategory_id']);
        } else {
            $sub_categories = null;
        }
        if (isset($row['discount_price']) && $row['discount_price'] != null) {$discount_price = $row['discount_price'];} else {
            $discount_price = null;
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
        $url = $row['cover'];

        $filename = basename(parse_url($url, PHP_URL_PATH));

        return new Product([

            'name' => $row['name'],
            'for' => 'store',
            'description' => $row['description'],
            'selling_price' => $row['selling_price'],

            'category_id' => Category::where('name', $row['category_id'])->where('is_deleted', 0)
                ->where('parent_id', null)
                ->where(function ($query) {
                    $query->where('store_id', auth()->user()->store_id)
                        ->OrWhere('store_id', null);
                })->pluck('id')->first(),
            'cover' => $filename,
            'SEOdescription' => $seo,
            'discount_price' => $discount_price,
            'subcategory_id' => $sub_categories == null ? null : implode(',', Category::whereIn('name', $sub_categories)->where('parent_id', $parent)->pluck('id')->toArray()),
            //'discount_percent'=>$row['discount_percent'],

            'stock' => $row['stock'],
            'short_description' => $row['short_description'],
            'snappixel' => $snappixel,
            'tiktokpixel' => $tiktokpixel,
            'twitterpixel' => $twitterpixel,
            'instapixel' => $instapixel,
            'robot_link' => $robot_link,
            'google_analytics' => $google_analytics,
            'weight' => $weight,

            'store_id' => auth()->user()->store_id,
        ]);

        // return $product;

    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string',
            '*.description' => 'required|string',
            '*.selling_price' => ['required', 'numeric', 'gt:0'],
            '*.short_description' => 'required|string|max:100',
            '*.stock' => ['required', 'numeric', 'gt:0'],
            // 'cover'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            '*.discount_price' => ['nullable', 'numeric'],
            // '*.discount_percent'=>['required','numeric'],
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
