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
         $success['new']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id)->where('order_status','new');
})->count();
        $success['completed']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id)->where('order_status','completed');
})->count();
        
         $success['not_completed']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id)->where('order_status','not_completed');
})->count();
          $success['canceled']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id)->where('order_status','canceled');
})->count();
        
           $success['all']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id);
})->count();
        
        $success['orders']=OrderResource::collection(Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id);
})->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الطلبات بنجاح','Orders return successfully');
    }
}
