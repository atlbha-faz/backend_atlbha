<?php

namespace App\Http\Controllers\api\storeDashboard;

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
            'offer_type'=>'required|in:If_bought_gets,fixed_amount,percent',
            'offer_title'=>'required|string',
            'offer_view' =>'required|in:store_website,store_application,both',
            'start_at'=>'required|date',
            'end_at'=>'required|date',
            'purchase_quantity' =>"required_if:offer_type,If_bought_gets",

            'purchase_type' =>'required_if:offer_type,If_bought_gets|in:product,category',
            'get_quantity' =>'required_if:offer_type,If_bought_gets|numeric',

            'get_type' =>'required_if:offer_type,If_bought_gets|in:product,category',
            'offer1_type' =>'required_if:offer_type,If_bought_gets|in:percent,free_product',
            'discount_percent' =>'required_if:offer1_type,percent',
            'discount_value_offer2' =>'required_if:offer_type,fixed_amount',
            'offer_apply' =>'required_if:offer_type,fixed_amount,percent|in:all,selected_product,selected_category,selected_payment',
            'offer_type_minimum' =>'required_if:offer_type,fixed_amount,percent',
             'offer_amount_minimum' =>'required_if:offer_type,fixed_amount,percent',
              'coupon_status' =>'required_if:offer_type,fixed_amount,percent',
             'discount_value_offer3' =>'required_if:offer_type,percent',
            'maximum_discount' =>'required_if:offer_type,percent',
            'product_id'=>'required_if:purchase_type,product',
            'get_product_id' =>'required_if:get_type,product',
            'category_id'=>'required_if:purchase_type,category',
            'get_category_id'=>'required_if:get_type,category',
            'select_product_id'=>"required_if:purchase_type,payment",
            'select_category_id'=>"required_if:purchase_type,payment",
            'select_payment_id'=>"required_if:purchase_type,payment",
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
             'maximum_discount' => $request->maximum_discount,
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
            $offer->paymenttypes()->attach(explode(',', $request->select_payment_id),["type" => "select"]);
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
        if (is_null($offer) || $offer->is_deleted==1){
        return $this->sendError("العرض غير موجود","offer is't exists");
        }


       $success['offers']=New OfferResource($offer);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض الخدمة  بنجاح','offer showed successfully');
    }


      public function changeStatus($id)
    {
        $offer = Offer::query()->find($id);
         if (is_null($offer) || $offer->is_deleted==1){
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
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'offer_type'=>'required|in:If_bought_gets,fixed_amount,percent',
            'offer_title'=>'required|string',
            'offer_view' =>'required|in:store_website,store_application,both',
            'start_at'=>'required|date',
            'end_at'=>'required|date',
            'purchase_quantity' =>"required_if:offer_type,If_bought_gets",
            'purchase_type' =>'required|in:product,category,payment',
            'get_quantity' =>'required_if:offer_type,If_bought_gets|numeric',
            'get_type' =>'required_if:offer_type,If_bought_gets|in:product,category',
            'offer1_type' =>'required_if:offer_type,If_bought_gets|in:percent,free_product',            'discount_percent' =>'required_if:offer1_type,percent',
            'discount_value_offer2' =>'required_if:offer_type,fixed_amount',
            'offer_apply' =>'required_if:offer_type,fixed_amount,percent',
            'offer_type_minimum' =>'required_if:offer_type,fixed_amount,percent',
             'offer_amount_minimum' =>'required_if:offer_type,fixed_amount,percent',
              'coupon_status' =>'required_if:offer_type,fixed_amount,percent',
             'discount_value_offer3' =>'required_if:offer_type,percent',
            'maximum_discount' =>'required_if:offer_type,percent',
            'product_id'=>'required_if:purchase_type,product',
            'get_product_id' =>'required_if:get_type,product',
            'category_id'=>'required_if:purchase_type,category',
            'get_category_id'=>'required_if:get_type,category',
            'select_product_id'=>"required_if:purchase_type,payment",
            'select_category_id'=>"required_if:purchase_type,payment",
            'select_payment_id'=>"required_if:purchase_type,payment",
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $offer->update([
            'offer_type' => $request->input('offer_type'),
            'offer_title' => $request->input('offer_title'),
            'offer_view' => $request->input('offer_view'),
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at'),
            'purchase_quantity' => $request->input('purchase_quantity'),
            'purchase_type' => $request->input('purchase_type'),
            'get_quantity' => $request->input('get_quantity'),
            'get_type' => $request->input('get_type'),
            'offer1_type' => $request->input('offer1_type'),
            'discount_percent' => $request->input('discount_percent'),
            'discount_value_offer2' => $request->input('discount_value_offer2'),
            'offer_apply' => $request->input('offer_apply'),
            'offer_type_minimum' => $request->input('offer_type_minimum'),
            'offer_amount_minimum' => $request->input('offer_amount_minimum'),
            'coupon_status' => $request->input('coupon_status'),
            'discount_value_offer3' => $request->input('discount_value_offer3'),
            'maximum_discount' => $request->input('maximum_discount'),

          ]);

          if($request->offer_type=="If_bought_gets"){

          switch($request->purchase_type) {
            case('category'):
                $offer->categories()->sync(explode(',', $request->category_id),["type" => "buy"]);
                $offer->categories()->sync(explode(',', $request->get_category_id),["type" =>"get" ]);

                break;
            case('product'):
               $offer->products()->sync(explode(',', $request->product_id),["type" => "buy"]);
                $offer->products()->sync(explode(',', $request->get_product_id),["type" => "get"]);
            break;

            }
        }
        else{
            switch($request->offer_apply) {
            case('selected_product'):
                $offer->products()->sync(explode(',', $request->select_product_id),["type" => "select"]);
                break;
            case('selected_category'):
            $offer->categories()->sync(explode(',', $request->select_category_id),["type" => "select"]);
            break;
             case('selected_payment'):
            $offer->paymenttypes()->sync(explode(',', $request->select_payment_id),["type" => "select"]);
            break;

          }
        }
         $success['offers']=New OfferResource($offer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل العرض بنجاح','offer updated successfully');
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
        if (is_null($offer) || $offer->is_deleted==1){
            return $this->sendError("العرض غير موجود","offer is't exists");
            }
           $offer->update(['is_deleted' => 1]);

           $success['offers']=New OfferResource($offer);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف العرض بنجاح','offer deleted successfully');
    }
}
