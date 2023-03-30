<?php

namespace App\Http\Controllers\api\storeDashboard;

use in;
use App\Models\Order;
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
        
         $success['sales']=DB::table('order_items')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->select(DB::raw('SUM(total_price - discount) as total'));
          $success['products']=Product::where('store_id',auth()->user()->store_id)->where('status','active')->where('is_deleted',0)->count();
        
        $success['orders']=OrderResource::collection(Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id);
})->orderBy('desc','created_at')->take(5));
        
        
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع بنجاح','return successfully');
    }
}
