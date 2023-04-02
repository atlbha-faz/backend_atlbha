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
        
        
          $success['products']=DB::table('order_items')->join('products', 'order_items.product_id', '=', 'products.id')->where('order_items.store_id',auth()->user()->store_id)
              
              
              ->select('products.id','products.cover','products.name','products.selling_price',DB::raw('sum(order_items.total_price - order_items.discount) as sales'),DB::raw('sum(order_items.quantity) as count'))
                 ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();
       
        
         $array_sales_monthly = array(); 
         $array_sales_weekly = array(); 
         $array_sales_daily = array(); 

        for($i = 1; $i <= 12; $i++){ 
            $array_sales_monthly[date('M', mktime(0, 0, 0, $i, 10))]= DB::table('order_items')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
       }
        
         for($i = 1; $i <= 12; $i++){ 
            $array_sales_weekly[date('M', mktime(0, 0, 0, $i, 10))]= DB::table('order_items')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
       }
        
         for($i = 1; $i <= 12; $i++){ 
            $array_sales_daily[date('Y-m-d', strtotime("-"+$i+" days"))]= DB::table('order_items')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereDate('created_at', date('Y-m-d' , strtotime('-1 days')))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
       }
        
        
        $success['array_sales_monthly']= $array_sales_monthly;
        $success['array_sales_weekly']= $array_sales_weekly;
        $success['array_sales_daily']= $array_sales_daily;
        
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع بنجاح','return successfully');
    }
}
