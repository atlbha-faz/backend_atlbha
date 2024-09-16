<?php

namespace App\Http\Controllers\api\homePages;
use App\Models\Websiteorder;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;

class ServiceController extends BaseController
{
    public function checkout(EtlobhaServiceRequest $request)
    {
        $order_number = Websiteorder::orderBy('id', 'desc')->first();
        if (is_null($order_number)) {
            $number = 0001;
        } else {

            $number = $order_number->order_number;
            $number = ((int) $number) + 1;
        }
        $websiteorder = Websiteorder::create([
            'type' => 'service',
            'order_number' => str_pad($number, 4, '0', STR_PAD_LEFT),
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'total_price'=>$request->total_price,
   
        ]);
        $websiteorder->services()->attach($request->service_id);
    }
}
