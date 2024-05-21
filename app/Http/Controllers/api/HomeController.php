<?php

namespace App\Http\Controllers\api;


use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Importproduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\LightCategoryResource;
use App\Http\Controllers\api\BaseController as BaseController;

class HomeController extends BaseController
{
    public function products(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 20;

        $products = Product::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->whereNot('stock', 0)->orderByDesc('created_at')->select('id', 'name', 'cover', 'selling_price', 'purchasing_price', 'stock', 'less_qty', 'created_at', 'category_id', 'short_description', 'subcategory_id');
        $products_pagintion = $products->paginate($count);
        $products_resources = ProductResource::collection($products_pagintion);
        $success['page_count'] = $products_pagintion->lastPage();
        $success['total_result'] = $products_pagintion->total();
        $success['current_page'] = $products_pagintion->currentPage();
        $success['products'] = $products_resources;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض المنتجات بنجاح', 'Products Added successfully');
    }

    public function categories(Request $request,$store_id)
    {
        $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();
        $store_id = $store->id;
        $product_ids = Importproduct::where('store_id', $store_id)->pluck('product_id')->toArray();
        $prodtcts = Product::whereIn('id', $product_ids)->where('is_deleted', 0)->where('status', 'active')->groupBy('category_id')->get();
        $category = array();
        foreach ($prodtcts as $prodtct) {
            $categoryOne = Category::with(['subcategory' => function ($query) use ($prodtct) {
                $query->whereIn('id', $prodtct->subcategory()->pluck('id')->toArray());
            }])->where('is_deleted', 0)->where('id', $prodtct->category_id
            )->where('status', 'active')->first();
            if ($categoryOne !== null) {
                $category[] = $categoryOne;
            }
        }
        ////////////////////////////////////////////////////////////////////
        $originalcategory = array();
        $originalcategory1 = array();
        $originalcategory2 = array();
        $originalProdutcts = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->get();
        foreach ($originalProdutcts as $originalProdutct) {
            $mainCategory = Category::with(['subcategory' => function ($query) use ($originalProdutct) {
                $query->whereIn('id', $originalProdutct->subcategory()->pluck('id')->toArray());
            }])->where('is_deleted', 0)->where('id', $originalProdutct->category_id
            )->where('store_id', null)->where('status', 'active')->first();
            if ($mainCategory !== null) {
                if (!empty($originalProdutct->subcategory()->pluck('id')->toArray())) {
                    $originalcategory1 = array_merge($originalcategory1, $originalProdutct->subcategory()->pluck('id')->toArray());
                }

                $originalcategory2[] = $mainCategory->id;

            }
        }
        $originalcategory1 = array_unique($originalcategory1);
        $originalcategory2 = array_unique($originalcategory2);

        $lastCategory = Category::with(['subcategory' => function ($query) use ($originalcategory1) {
            $query->whereIn('id', $originalcategory1);
        }])->where('is_deleted', 0)->where('id', $originalcategory2
        )->where('store_id', null)->where('status', 'active')->get();

        $categories = Category::where('is_deleted', 0)->where('status', 'active')->where('parent_id', null)
            ->where('store_id', $store_id)->get()->merge($category)->concat($lastCategory);

        if ($categories != null) {
            $success['categories'] = CategoryResource::collection($categories);
        } else {
            $success['categories'] = array();
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض التصنيفات بنجاح', 'categories showed successfully');
    }


}
