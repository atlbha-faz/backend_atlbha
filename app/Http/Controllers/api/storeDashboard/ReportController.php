<?php

namespace App\Http\Controllers\api\storeDashboard;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;

class ReportController extends BaseController
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $success['total_sales']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('total_price');
        $success['products_costs']=Product::where('store_id',auth()->user()->store_id)->where('is_deleted',0)->sum('purchasing_price');
        $success['discount_coupons']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('discount');
        // $success['shipping_price']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('tax');
        $success['taxs']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('tax');
        $success['payment']=0;
        $success['sales']=$success['total_sales']+$success['products_costs']+  $success['discount_coupons']+$success['taxs']+$success['payment'];
        $success['status']= 200;
         return $this->sendResponse($success,'تم ارجاع التقارير بنجاح','Reports return successfully');
    }
}
