<?php

namespace App\Http\Controllers\api\storeDashboard;

use DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexController extends BaseController
{
      public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
      {
         $success['visits']=10;
        $success['customers']=User::where('user_type', 'customer')->where('status','active')->where('is_deleted',0)->where('verified',1)->count();
        
        $success['sales']=DB::table('order_items')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
          $success['products_count']=Product::where('store_id',auth()->user()->store_id)->where('status','active')->where('is_deleted',0)->count();
        
        $success['orders']=OrderResource::collection(Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id);
})->orderBy('created_at', 'DESC')->take(5)->get());
        
        
          $success['products']=DB::table('order_items')->where('store_id',auth()->user()->store_id)
              ->join('products', 'order_items.product_id', '=', 'products.id')
              
              ->select('order_items.product_id', 'products.image','products.name','products.price', DB::raw('count(*) as total'))
                 ->groupBy('order_items.product_id')->orderBy('total', 'desc')->get();
       
        
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع بنجاح','return successfully');
    }
}
