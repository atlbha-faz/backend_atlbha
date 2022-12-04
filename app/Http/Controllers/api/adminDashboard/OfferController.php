<?php

namespace App\Http\Controllers\api\adminDashboard;

use in;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Resources\OfferResource;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class OfferController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
      {
        $success['offers']=OfferResource::collection(Offer::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع العروض بنجاح','offers return successfully');
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
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'offer_type'=>'required',
            'offer_title'=>'required|string',
            'offer_view' =>'required|string',
            'start_at'=>'required|date',
            'end_at'=>'required|date',
            'purchase_quantity' =>"required_if:offer_type,If_bought_gets",
            'purchase_type' =>'required_if:offer_type,If_bought_gets',
            'get_quantity' =>'required_if:offer_type,If_bought_gets|numeric',
            'get_type' =>'required_if:offer_type,If_bought_gets',
            'offer1_type' =>'required_if:offer_type,If_bought_gets',
            'discount_percent' =>'required_if:offer1_type,percent',
            'discount_value_offer2' =>'required_if:offer_type,fixed_amount',
            'offer_apply' =>'required_if:offer_type,fixed_amount,percent',
            'offer_type_minimum' =>'required_if:offer_type,fixed_amount,percent',
             'offer_amount_minimum' =>'required_if:offer_type,fixed_amount,percent',
              'coupon_status' =>'required_if:offer_type,fixed_amount,percent',
             'discount_value_offer3' =>'required_if:offer_type,percent',
            'maximum_discount' =>'required_if:offer_type,percent',
            'get' =>'required_if:offer_type,percent'

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $offer = Offer::create([
            'offer_type' => $request->offer_type,
            'offer_title'=>$request->offer_title,
            'offer_view' =>$request->offer_view,
            'start_at' => $request->start_at,
            'end_at'=>$request->end_at,
            'purchase_quantity' =>$request->purchase_quantity,
            'purchase_type' =>$request->purchase_type,
            'get_quantity' =>$request->get_quantity,
            'get_type' =>$request->get_type,
            'offer1_type' =>$request->offer1_type,
            'discount_percent' => $request->discount_percent,
            'discount_value_offer2' => $request->discount_value_offer2,
             'offer_apply' => $request->offer_apply,
             'offer_type_minimum' => $request->offer_type_minimum,
             'offer_amount_minimum' => $request->offer_amount_minimum,
             'coupon_status' => $request->coupon_status,
             'discount_value_offer3' => $request->discount_value_offer3,
             'maximum_discount' => $request->maximum_discount
          ]);
          if($request->offer_type=="If_bought_gets"){
          switch($request->purchase_type) {
            case('category'):
                $offer->categories()->attach(explode(',', $request->category_id),["type" => "buy"]);
                $offer->categories()->attach(explode(',', $request->get_category_id),["type" =>"get" ]);

                break;
            case('product'):
               $offer->products()->attach(explode(',', $request->product_id),["type" => "buy"]);
                $offer->products()->attach(explode(',', $request->get_product_id),["type" => "get"]);
            break;

            }
        }
        else{
            switch($request->offer_apply) {
            case('selected_product'):
                $offer->products()->attach(explode(',', $request->select_product_id),["type" => "select"]);
                break;
            case('selected_category'):
            $offer->categories()->attach(explode(',', $request->select_category_id),["type" => "select"]);
            break;
             case('selected_payment'):
            $offer->paymenttypes()->attach(explode(',', $request->select_payment_id));
            break;

          }
        }
         $success['offers']=New OfferResource($offer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة العرض بنجاح','offer Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show($offer)
   {
        $offer = Offer::query()->find($offer);
        if ($offer->is_deleted==1){
        return $this->sendError("العرض غير موجود","offer is't exists");
        }


       $success['offers']=New OfferResource($offer);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض الخدمة  بنجاح','offer showed successfully');
    }


      public function changeStatus($id)
    {
        $offer = Offer::query()->find($id);
         if ($offer->is_deleted==1){
         return $this->sendError(" العرض غير موجود","offer is't exists");
         }

        if($offer->status === 'active'){
            $offer->update(['status' => 'not_active']);
        }
        else{
            $offer->update(['status' => 'active']);
        }
        $success['offers']=New OfferResource($offer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة العرض بنجاح','offer updated successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy($offer)
     {
        $offer = Offer::query()->find($offer);
        if ($offer->is_deleted==1){
            return $this->sendError("العرض غير موجود","offer is't exists");
            }
           $offer->update(['is_deleted' => 1]);

           $success['offers']=New OfferResource($offer);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف العرض بنجاح','offer deleted successfully');
    }
}
