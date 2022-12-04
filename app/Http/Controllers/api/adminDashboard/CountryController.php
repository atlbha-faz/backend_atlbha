<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Resources\CountryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class CountryController extends  BaseController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['countries']=CountryResource::collection(Country::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الدول بنجاح','countries return successfully');

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
            'code' =>'required',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $country = Country::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'code' => $request->code,
          ]);

         // return new CountryResource($country);
         $success['countries']=New CountryResource($country);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة دولة بنجاح','country Added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        $country = Country::query()->find($country->id);
  if ($country->is_deleted==1){
         return $this->sendError("الدولة غير موجودة","country is't exists");
         }
        $success['$countries']=New CountryResource($country);
        $success['status']= 200;

         return $this->sendResponse($success,'تم  عرض بنجاح','country showed successfully');

    }

     public function changeStatus($id)
    {
        $country = Country::query()->find($id);
        if ($country->is_deleted==1){
         return $this->sendError("الدولة غير موجودة","country is't exists");
         }
        if($country->status === 'active'){
     $country->update(['status' => 'not_active']);
     }
    else{
        $country->update(['status' => 'active']);
    }
        $success['$countries']=New CountryResource($country);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة الدولة بنجاح','country status updared successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        // $country = Country::query()->find($id);
        if ($country->is_deleted==1){
         return $this->sendError("الدولة غير موجودة","country is't exists");
         }
        $input = $request->all();
        $validator =  Validator::make($input ,[
          'name'=>'required|string|max:255',
            'name_en'=>'required|string|max:255',
            'code' =>'required'
        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $country->update([
            'name' => $request->input('name'),
            'name_en' => $request->input('name_en'),
            'code' => $request->input('code')
        ]);
       //$country->fill($request->post())->update();
        $success['countriesd']=New CountryResource($country);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        if ($country->is_deleted==1){
         return $this->sendError("الدولة غير موجودة","country is't exists");
         }

            $country->update(['is_deleted' => 1]);

        $success['country']=New CountryResource($country);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف الدولة بنجاح','country deleted successfully');

    }
}