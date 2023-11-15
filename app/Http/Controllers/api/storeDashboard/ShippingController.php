<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ShippingController extends BaseController
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
        //
    }
    //  public function creatOrder(Request $request,$orderId)
    //  {
    // //     $order =Order::where('id',$orderId)->first();
    // //     $input = $request->all();
    // //     $validator =  Validator::make($input ,[
    // //         'weight'=>'required',
    // //         'city'=>'required|string|max:255',
    // //         'streetaddress'=>'required|string|max:255',
    // //     ]);
    // //     if ($validator->fails())
    // //     {
    // //         return $this->sendError(null,$validator->errors());
    // //     }
        
    // //     if( $order->payment_status=='paid'|| $order->cod ==1)
    // //     { 
    // //         if($order->shippingtype->name == "Saee"){
    // //        $data = array(
    // //               'userId'=>'651d6aa9caff20c31d7404fc',
    // //               'apiKey' =>'BWdZr8nu!f%0#B7%pWOtUbkUKFV#m4ZvwQYXedBmclcG3H%SA$$ICRFK8Q9rBq$aMKZyI!yA9V1kL!eMhpvzOMBmPV8hn6yAI%mwNVv56F8U19cIG#bUmsc6$nNc#6R7TpMNHutyiSQlpnm3RJJA1P',
    // //               'cashonpickup'=> 0,
    // //             'cashondelivery' => 1,
    // //             'p_name'=>auth()->user()->store->store_name,
    // //             'p_mobile'=> auth()->user()->phonenumber,
    // //             'p_city' => $request->city,
    // //             'p_streetaddress' => $request->streetaddress,
    // //             'c_name' => $order->user->name,
    // //             'c_mobile' => $order->user->phonenumber,
    // //             'c_streetaddress' => $order->shippingAddress->street_address,
    // //             'c_city' => $order->shippingAddress->city,
    // //             'weight' => $request->weight,
    // //             'quantity' => $order->quantity,
    // //           'Description'=>$order->description
               
    // //            );
    // //            $new_data = json_encode($data);
    // //        $curl = curl_init();

    // //      curl_setopt_array($curl, array(
    // //      CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/saee/create-user-order',
    // //      CURLOPT_RETURNTRANSFER => true,
    // //      CURLOPT_ENCODING => '',
    // //      CURLOPT_MAXREDIRS => 10,
    // //      CURLOPT_TIMEOUT => 0,
    // //      CURLOPT_FOLLOWLOCATION => true,
    // //      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    // //      CURLOPT_CUSTOMREQUEST => 'POST',
    // //       CURLOPT_POSTFIELDS =>$new_data,
    // //      CURLOPT_HTTPHEADER => array(
    // //        'Content-Type: application/json'
    // //              ),
    // //           ));

    // //     $response = curl_exec($curl);

    // //         curl_close($curl);
    // //      $ship=json_decode($response);
    // //     $success['shipping']=json_decode($response);
    // //     $shipping=Shipping::create([
    // //      'description' => $order->description,
    // //      'quantity' => $order->quantity,
    // //    'price'=>$order->total_price,
    // //    'weight'=>$request->weight,
    // //    'customer_id'=>$order->customer_id,
    // //    'shippingtype_id'=>$order->shippingtype_id,
    // //    'order_id'=>$order->order_id,
    // //    'shipping_status'=>"delivery_in_progress",
    // //    'store_id'=>$order->store_id,
    // //    'cashondelivery'=>$order->cashondelivery,
    // //         ]);
      
    // //     }
    //  }
    //     $success['shipping']=json_decode($response);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم إرجاع الشحنه', ' shipping successfully');
    // }

    public function getAllCity()
    {
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
          CURLOPT_POSTFIELDS =>'{
            "userId": "651d6aa9caff20c31d7404fc",
            "apiKey": "BWdZr8nu!f%0#B7%pWOtUbkUKFV#m4ZvwQYXedBmclcG3H%SA$$ICRFK8Q9rBq$aMKZyI!yA9V1kL!eMhpvzOMBmPV8hn6yAI%mwNVv56F8U19cIG#bUmsc6$nNc#6R7TpMNHutyiSQlpnm3RJJA1P"
        }
        ',
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

    public function printSticker($id)
    {
    
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
            CURLOPT_POSTFIELDS =>'{
                "userId": "651d6aa9caff20c31d7404fc",
                "apiKey": "BWdZr8nu!f%0#B7%pWOtUbkUKFV#m4ZvwQYXedBmclcG3H%SA$$ICRFK8Q9rBq$aMKZyI!yA9V1kL!eMhpvzOMBmPV8hn6yAI%mwNVv56F8U19cIG#bUmsc6$nNc#6R7TpMNHutyiSQlpnm3RJJA1P"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $success['cities'] = json_decode( $response);
            $success['status'] = 200;

        return $this->sendResponse($success, 'تم الإرجاع بنجاح', ' print Sticker successfully');

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function show(Shipping $shipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipping $shipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $shipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {
        //
    }
}
