<?php

namespace App\Http\Controllers\api;


use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\api\BaseController as BaseController;

class HomeController extends BaseController
{
    public function products(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 20;

        $products = Product::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->whereNot('stock', 0)->orderByDesc('created_at')->select('id', 'name', 'cover', 'selling_price', 'purchasing_price', 'stock', 'less_qty', 'created_at', 'category_id', 'subcategory_id');
        $products_pagintion = $products->paginate($count);
        $products_resources= ProductResource::collection($products_pagintion);
        $success['page_count'] =   $products_pagintion->lastPage();
        $success['total_result'] =   $products_pagintion->total();
        $success['current_page'] =   $products_pagintion->currentPage();
        $success['products'] =  $products_resources;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض المنتجات بنجاح', 'Products Added successfully');
    }
}
