<?php

namespace App\Http\Controllers\api\adminDashboard;


use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Resources\CurrencyResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class CurrencyController extends BaseController
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
        $success['Currencies']=CurrencyResource::collection(Currency::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع العملات بنجاح',' Currencies return successfully');
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
            'name'=>'required|string|max:255',
            'name_en'=>'required|string|max:255',
            'image' =>'required',
       
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $currency = Currency::create([
            'name' => $request->name,
            'name_en'=>$request->name_en,
            'image' =>$request->image,
          
          ]);

         $success['currencies']=New CurrencyResource($currency);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة العملة بنجاح',' Currency Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        
             $currency = Currency::query()->find($currency->id);
             if (is_null($currency) || $currency->is_deleted==1){
             return $this->sendError("العملة غير موجودة"," Currency is't exists");
             }
    
    
            $success['currencies']=New CurrencyResource($currency);
            $success['status']= 200;
    
             return $this->sendResponse($success,'تم  عرض بنجاح',' Currency showed successfully');
    
        }
        public function changeStatus($id)
        {
            $currency =Currency::query()->find($id);
             if (is_null($currency) || $currency->is_deleted==1){
             return $this->sendError("العملة غير موجودة","currency is't exists");
             }
    
            if($currency->status === 'active'){
            $currency->update(['status' => 'not_active']);
            }
            else{
            $currency->update(['status' => 'active']);
            }
            $success['currencies']=New CurrencyResource($currency);
            $success['status']= 200;
    
             return $this->sendResponse($success,'تم تعديل حالة العملة بنجاح','currency updated successfully');
    
        }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        if (is_null($currency) || $currency->is_deleted==1){
         return $this->sendError("العملة غير موجودة","currency is't exists");
    }
         $input = $request->all();
        $validator =  Validator::make($input ,[
             'name'=>'required|string|max:255',
            'name_en'=>'required|string|max:255',
            'image' =>'required',
         
        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $currency->update([
            'name' => $request->input('name'),
            'name_en' => $request->input('name_en'),
            'image' => $request->input('image'),
         
        ]);
       
        $success['Currencies']=New CurrencyResource($currency);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
          if (is_null($currency) || $currency->is_deleted==1){
         return $this->sendError("العملة غير موجودة","currency is't exists");
         }
        $currency->update(['is_deleted' => 1]);

        $success['currencies']=New CurrencyResource($currency);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف العملة بنجاح','currency deleted successfully');
    }
}