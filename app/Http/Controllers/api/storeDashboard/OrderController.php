<?php

namespace App\Http\Controllers\api\storeDashboard;

use in;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class OrderController extends BaseController
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

public function show($order)
   {
       $order = Order::where('id',$order)->whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id);
})->first();
        if (is_null($order)){
        return $this->sendError("'الطلب غير موجود","Order is't exists");
        }


       $success['orders']=New OrderResource($order);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض الطلب بنجاح','Order showed successfully');
}
    
     public function update(Request $request, $order)
      {
              $order = Order::where('id',$order)->whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id);
})->first();
        if (is_null($order)){
        return $this->sendError("'الطلب غير موجود","Order is't exists");
        }
         
         $input = $request->all();
         $validator =  Validator::make($input ,[
           'status'=>'required|in:new,completed,delivery_in_progress,ready,canceled',
         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         }
         $order->update([
            'order_status' => $request->input('status'),
         ]);
         
          $order->items()->update([
            'order_status' => $request->input('status'),
         ]);
            $success['orders']=New OrderResource($order);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','Order updated successfully');
    }
    
}
