<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function products()
    {
        $products = ProductResource::collection(Product::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->whereNot('stock', 0)->orderByDesc('created_at')->select('id', 'name', 'cover', 'selling_price', 'purchasing_price', 'stock', 'less_qty', 'created_at', 'category_id', 'subcategory_id')->paginate(10));
        return (new BaseController())->sendResponse($products->response()->getData(true), 'تم الحصول على المنتاجات', 'Products retrieved successfully.');

    }
}
