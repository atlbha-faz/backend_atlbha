<?php

namespace App\Http\Controllers\api\storeDashboard;

use in;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\OrderAddress;
use App\Models\OrderOrderAddress;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Services\SaeeService;
use App\Services\SmsaService;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ShippingtypeResource;
use Illuminate\Support\Facades\Cache;
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
         $success['new']=Order::where('store_id',auth()->user()->store_id)->where('order_status','new')->count();
        $success['completed']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id)->where('order_status','completed');
})->count();
        
         $success['not_completed']=Order::where('store_id',auth()->user()->store_id)->where('order_status','not_completed')->count();
          $success['canceled']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id)->where('order_status','canceled');
})->count();
        
           $success['all']=Order::whereHas('items', function($q){
    $q->where('store_id',auth()->user()->store_id);
})->count();

$storeID =
      
  $data = OrderResource::collection(Order::with(['user','shipping','shippingtype','items' => function ($query) {
    $query->select('id');
}])->where('store_id',  auth()->user()->store_id)->orderByDesc('id')->get(['id','user_id','shippingtype_id','total_price','quantity','order_status']));
  
       $success['orders']=$data;
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
          
           'city'=>'required_if:status,==,ready',
           'street_address'=>'required_if:status,==,ready',
         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         } 
      
          if( $request->input('status') !=="ready"){
        
        $order->update([
           'order_status' => $request->input('status'),
        ]);
        foreach ( $order->items as $orderItem) {
         $orderItem->update([
           'order_status' => $request->input('status'),
        ]);
       }
       if( $order->order_status=="canceled")
       {
        $shippings=Shipping::where('order_id',$order->id)->first();
        if( $shippings != null){
        $shippings->update([
        'shipping_status'=>"canceled",
        ]);
        $shippings->shipping_id;

        if( $order->shippingtype->id == 1)
        {
        $curl = curl_init();
        $data = array(
          'userId'=>env('GOTEX_UserId_KEY'),
          'apiKey' =>env('GOTEX_API_KEY'),
          'orderId'=> $shippings->shipping_id);
          $new_data = json_encode($data);
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/saee/cancel-order',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$new_data,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
          $success['shippingCompany']=json_decode($response);
        }
        $success['shipping']=New shippingResource($shippings);
        }
       }

      }else{
         if( $request->status==="ready")
         {
       $orderAddress=OrderOrderAddress::where('order_id',$order->id)->where('type','shipping')->value('order_address_id');
 
       $address=OrderAddress::where('id', $orderAddress)->first();
        //  if( $order->payment_status=='paid'|| $order->cod ==1)
        //  { 
             if($order->shippingtype->id == 2){
              
              $data = array(
                'userId'=>env('GOTEX_UserId_KEY'),
                'apiKey' =>env('GOTEX_API_KEY'),
              'c_name' => $order->user->name,
              'c_ContactPhoneNumber' => $order->user->phonenumber,
              'c_District' =>  $address->district,
              'c_City' =>  $address->city,
              'c_AddressLine1' =>  $address->street_address,
              'p_name'=>auth()->user()->store->store_name,
              'p_ContactPhoneNumber'=> auth()->user()->phonenumber,
              'p_District' => $request->district,
              'p_City' => $request->city,
              'p_AddressLine1' => $request->street_address,
              'pieces' =>  1,
              'weight' =>$order->weight,
              'description'=>$order->description,
              'value' =>$order->total_price,
               'Cod'=> true,
               'shipmentValue'=>$order->total_price
             );
            $smsa =new SmsaService();
             $smsaData=$smsa->createOrder($data);
          $ship=$smsaData;
          $success['shippingCompany']=$ship;
          if(isset($ship->data)){
            $ship_id=$ship->data->_id;
            $track_id=$ship->data->data->sawb;
             $order->update([
              'order_status' => $request->input('status'),
           ]);
           foreach ( $order->items as $orderItem) {
            $orderItem->update([
              'order_status' => $request->input('status'),
           ]);
           }
          }
            else{
       
              $ship_id=null;
              $track_id=null;
         return $this->sendError("لا يمكن إنشاء شحنة بسب عدم وجود رصيد", "cant create shipping");


            }
         $shipping=Shipping::create([
          'shipping_id'=>$ship_id,
          'track_id'=>$track_id,
          'description' => $order->description,
          'quantity' => $order->quantity,
        'price'=>$order->total_price,
        'weight'=>$request->weight,
         'district'=>$request->district,
        'city'=>$request->city,
        'streetaddress'=>$request->street_address,
        'customer_id'=>$order->user_id,
        'shippingtype_id'=>$order->shippingtype_id,
        'order_id'=>$order->id,
        'shipping_status'=>"ready",
        'store_id'=>$order->store_id,
        'cashondelivery'=>$order->cashondelivery,
             ]);
             $success['shipping']=New shippingResource($shipping);
         }
       
         elseif( $order->shippingtype->id == 1){
             
          $data = array(
            'userId'=>env('GOTEX_UserId_KEY'),
            'apiKey' =>env('GOTEX_API_KEY'),
          'c_name' => $order->user->name,
          'c_mobile' => $order->user->phonenumber,
          'c_city' => $address->city,
          'c_streetaddress' => $address->street_address,
          'p_name'=>auth()->user()->store->store_name,
          'p_mobile'=> auth()->user()->phonenumber,
          'p_city' => $request->city,
          'p_streetaddress' => $request->street_address,
          'quantity' =>1,
          'weight' => $order->weight,
          'description'=>$order->description,
          'cashondelivery' =>0,
          'cashonpickup'=> 0,
            'Cod'=> true,
        'shipmentValue'=>$order->total_price
        
         );
         $Saee=new SaeeService();
             $SaeeData=$Saee->createOrder($data);
          $ship=$SaeeData;
         
          $success['shippingCompany']=$ship;
          if(isset($ship->data))
           {
          $ship_id=$ship->data->_id;
          $track_id=$ship->data->data->waybill;
                   $order->update([
              'order_status' => $request->input('status'),
           ]);
           foreach ( $order->items as $orderItem) {
            $orderItem->update([
              'order_status' => $request->input('status'),
           ]);
           }
        }
          else{
            $ship_id=null;
            $track_id=null;
            return $this->sendError("لا يمكن إنشاء شحنة بسب عدم وجود رصيد", "cant create shipping");

          }
         $shipping=Shipping::create([
          'shipping_id'=>$ship_id,
          'track_id'=>$track_id,
          'description' => $order->description,
          'quantity' => $order->quantity,
        'price'=>$order->total_price,
        'weight'=>$request->weight,
           'district'=>$request->district,
        'city'=>$request->city,
        'streetaddress'=>$request->street_address,
        'customer_id'=>$order->user_id,
        'shippingtype_id'=>$order->shippingtype_id,
        'order_id'=>$order->id,
        'shipping_status'=>"ready",
        'store_id'=>$order->store_id,
        'cashondelivery'=>$order->cashondelivery,
             ]);
             $success['shipping']=New shippingResource($shipping);

            }
        
        //   }
         }
      } 
          $success['orders']=New OrderResource($order);
       
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','Order updated successfully');
    }

    public function getAllCity()
    {
          $key= array(
            'userId'=>env('GOTEX_UserId_KEY'),
            'apiKey'=>env('GOTEX_API_KEY')
           );
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/saee/get-cities',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($key),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
      $response;
        $success['cities'] = json_decode( $response);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إرجاع المدن', ' cities successfully');
    }

    public function PrintSaeeSticker($id)
    {
          $key= array(
            'userId'=>env('GOTEX_UserId_KEY'),
            'apiKey'=>env('GOTEX_API_KEY')
           );
    
           $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/saee/print-sticker/'.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($key),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $success['Sticker'] = json_decode( $response);
            $success['status'] = 200;

        return $this->sendResponse($success, 'تم الإرجاع بنجاح', ' print Sticker successfully');

    }
    public function PrintSmsaSticker($id)
    {
             $key= array(
            'userId'=>env('GOTEX_UserId_KEY'),
            'apiKey'=>env('GOTEX_API_KEY')
           );
           $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/smsa/print-sticker/'.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($key),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $success['Sticker'] = json_decode( $response);
            $success['status'] = 200;

        return $this->sendResponse($success, 'تم الإرجاع بنجاح', ' print Sticker successfully');

    }
  
     public function deleteall(Request $request)
    {

            $orders =Order::whereIn('id',$request->id)->whereHas('items', function($q){
           $q->where('store_id',auth()->user()->store_id);
                                       })->get();
           foreach($orders as $order)
           {
            if (is_null($order)){
                   return $this->sendError("الطلب غير موجود"," Order is't exists");
             }
               $items = OrderItem::where('store_id' , auth()->user()->store_id)->where('order_id', $order->id)->get();
               foreach($items as $item)
           {
               $item->delete();
               }
            }
               $success['orders']= OrderResource::collection($orders);
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف الطلبات بنجاح','Order deleted successfully');
    }
    
    
}
