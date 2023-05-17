<?php

namespace App\Http\Controllers\api\storeDashboard;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ReportController extends BaseController
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    { $input = $request->all();

        $validator =  Validator::make($input ,[
            'startDate'=>'date',
            'endDate'=>'date',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $startDate=$request->startDate;
        $endDate=$request->endDate;
        if(is_null($request->startDate) || is_null($request->endDate)){


             $success['total_sales']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('total_price');
        $success['products_costs']=Product::where('store_id',auth()->user()->store_id)->where('is_deleted',0)->sum('purchasing_price');
        $success['discount_coupons']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('discount');
        $success['shipping_price']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('tax');
        $success['taxs']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->sum('tax');
        $success['payment']=0;
        $success['sales']=$success['total_sales']+$success['products_costs']+  $success['discount_coupons']+$success['taxs']+$success['payment']+$success['shipping_price'];
        $success['status']= 200;
         return $this->sendResponse($success,'تم ارجاع التقارير بنجاح','Reports all return successfully');


        }
        else{
             $success['total_sales']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->sum('total_price');
        $success['products_costs']=Product::where('store_id',auth()->user()->store_id)->where('is_deleted',0)->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->sum('purchasing_price');
        $success['discount_coupons']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->sum('discount');
         $success['shipping_price']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->sum('tax');
        $success['taxs']=Order::where('store_id',auth()->user()->store_id)->where('order_status','completed')->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->sum('tax');
        $success['payment']=0;
        $success['sales']=$success['total_sales']+$success['products_costs']+  $success['discount_coupons']+$success['taxs']+$success['payment']+$success['shipping_price'];
        $success['status']= 200;
         return $this->sendResponse($success,'تم ارجاع التقارير بنجاح','Reports return successfully');

        }
    }
}
