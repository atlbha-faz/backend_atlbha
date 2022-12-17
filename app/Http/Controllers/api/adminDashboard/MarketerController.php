<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Marketer;
use Illuminate\Http\Request;
use App\Http\Resources\MarketerResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class MarketerController extends BaseController
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
      $success['marketers']=MarketerResource::collection(Marketer::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المندوبين بنجاح','marketer return successfully');

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
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'gender'=>'required|in:male,female',
            'phoneNumber'=>'required|numeric',
            'snapchat'=>'required|url',
            'facebook'=>'required|url',
            'twiter'=>'required|url',
            'whatsapp'=>'required',
            'youtube'=>'required|url',
            'instegram'=>'required|url',
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
             'socialmediatext' =>'string'

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $marketer = Marketer::create([
            'name'=> $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'gender' => $request->gender,
            'phoneNumber' => $request->phoneNumber,
            'facebook' => $request->facebook,
            'snapchat' => $request->snapchat,
            'twiter' => $request->twiter,
            'whatsapp' => $request->whatsapp,
            'youtube' => $request->youtube,
            'instegram' => $request->instegram,
             'image' => $request->image,
             'country_id' =>$request->country_id,
             'city_id' =>$request->city_id,
             'socialmediatext'=>$request->socialmediatext
          ]);

         // return new CountryResource($country);
         $success['marketers']=New MarketerResource($marketer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة مندوب بنجاح','marketer Added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function show(Marketer $marketer)
    {
       $marketer = Marketer::query()->find($marketer->id);
    if (is_null($marketer) || $marketer->is_deleted==1){
         return $this->sendError("المندوب غير موجودة","marketer is't exists");
         }
        $success['$marketers']=New MarketerResource($marketer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم  عرض بنجاح','marketer showed successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function edit(Marketer $marketer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marketer $marketer)
    {

         $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'gender'=>'required|in:male,female',
            'phoneNumber'=>'required|numeric',
            'snapchat'=>'required',
            'facebook'=>'required',
            'twiter'=>'required',
            'whatsapp'=>'required',
            'youtube'=>'required',
            'instegram'=>'required',
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
          'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'socialmediatext' =>'string'

        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $marketer->update([
            'name'=> $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'gender' => $request->input('gender'),
            'phoneNumber' => $request->input('phoneNumber'),
            'facebook' => $request->input('facebook'),
            'snapchat' => $request->input('snapchat'),
            'twiter' => $request->input('twiter'),
            'whatsapp' => $request->input('whatsapp'),
            'youtube' => $request->input('youtube'),
            'instegram' => $request->input('instegram'),
             'image' => $request->input('image'),
             'country_id' =>$request->input('country_id'),
             'city_id' =>$request->input('city_id'),
             'socialmediatext' =>$request->input('socialmediatext')
        ]);

        $success['marketers']=New MarketerResource($marketer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','modify  successfully');

    }

      public function changeStatus($id)
    {
        $marketer = Marketer::query()->find($id);
        if (is_null($marketer) || $marketer->is_deleted==1){
         return $this->sendError("المندوب غير موجودة","marketer is't exists");
         }
        if($marketer->status === 'active'){
         $marketer->update(['status' => 'not_active']);
        }
      else{
      $marketer->update(['status' => 'active']);
          }
        $success['$marketers']=New MarketerResource($marketer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعدبل حالة المندوب  بنجاح','marketer status updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marketer $marketer)
    {
       if (is_null($marketer) || $marketer->is_deleted==1){
         return $this->sendError("المندوب غير موجودة","marketer is't exists");
         }
            $marketer->update(['is_deleted' => 1]);


         $success['marketers']=New MarketerResource($marketer);

        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف المسوق  بنجاح','Marketers deleted successfully');

    }
}