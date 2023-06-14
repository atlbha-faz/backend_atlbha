<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
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
      $success['marketers']=MarketerResource::collection(Marketer::whereHas('user', function($q){
    $q->where('is_deleted', 0);
})->get());
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
          'checkbox_field' => 'required|in:1',
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
             'password_confirm' => 'required|same:password',
            'user_name'=>'required|string|max:255',
            'phonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'snapchat'=>'required|url',
            'facebook'=>'required|url',
            'twiter'=>'required|url',
            'whatsapp'=>['required','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'youtube'=>'required|url',
            'instegram'=>'required|url',
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'status'=>'required|in:active,not_active',
            // 'socialmediatext' =>'string'

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $user = User::create([
          'name'=> $request->name,
          'user_name'=> $request->user_name,
          'email' => $request->email,
          'password' => $request->password,
          'phonenumber' => $request->phonenumber,
          'gender' => $request->gender,
          'image' => $request->image,
          'country_id' =>$request->country_id,
          'city_id' =>$request->city_id,
            'status' => $request->status,
         'user_type' => "marketer",
      ]);
      $marketer = Marketer::create([
            'user_id'=> $user->id,
            'facebook' => $request->facebook,
            'snapchat' => $request->snapchat,
            'twiter' => $request->twiter,
            'whatsapp' => $request->whatsapp,
            'youtube' => $request->youtube,
            'instegram' => $request->instegram,
            // 'socialmediatext'=>$request->socialmediatext
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
       $user= User::query()->find($marketer->user_id);
    if (is_null($marketer) || $user->is_deleted==1){
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
    public function update(Request $request,  $marketer)
    {
      $marketer =  Marketer::where('id', $marketer)->first();
      $user= User::query()->find($marketer->user_id);
      if (is_null($user) || $user->is_deleted==1){
        return $this->sendError(" المندوب غير موجود","marketer is't exists");
         }
         $input = $request->all();
       $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$marketer->user->id,
            'password'=>'nullable|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
             'password_confirm' => 'nullable|same:password',
            'user_name'=>'required|string|max:255',
            'phonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'snapchat'=>'required',
            'facebook'=>'required',
            'twiter'=>'required',
            'whatsapp'=>'required',
            'youtube'=>'required',
            'instegram'=>'required',
            'image'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
             'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'status'=>'required|in:active,not_active',
           // 'socialmediatext' =>'string'

        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $user= User::query()->find($marketer->user_id);
        $user->update([
          'name'=> $request->name,
          'user_name'=> $request->user_name,
          'email' => $request->email,
          'password' => $request->password,
          'phonenumber' => $request->phonenumber,
          'gender' => $request->gender,
          'image' => $request->image,
          'country_id' =>$request->country_id,
          'city_id' =>$request->city_id,
          'status' =>$request->status,
      ]);
        $marketer->update([
            'facebook' => $request->input('facebook'),
            'snapchat' => $request->input('snapchat'),
            'twiter' => $request->input('twiter'),
            'whatsapp' => $request->input('whatsapp'),
            'youtube' => $request->input('youtube'),
            'instegram' => $request->input('instegram'),
            // 'socialmediatext' =>$request->input('socialmediatext')
        ]);
        
        if(!is_null($request->password)){
            $user->update([
          'password'=> $request->password,
      ]);
        }

        $success['marketers']=New MarketerResource($marketer);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','modify  successfully');

    }

      public function changeStatus($id)
    {
        $marketer = Marketer::query()->find($id);
        $user= User::query()->find($marketer->user_id);
        if (is_null($user) || $user->is_deleted==1){
         return $this->sendError("المندوب غير موجودة","user is't exists");
         }
        if($user->status === 'active'){
         $user->update(['status' => 'not_active']);
        }
      else{
      $user->update(['status' => 'active']);
          }
        $success['marketers']=New MarketerResource($marketer);
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
      $user= User::query()->find($marketer->user_id);
       if (is_null($user) || $user->is_deleted==1){
         return $this->sendError("المندوب غير موجودة","user is't exists");
         }
            $user->update(['is_deleted' => 1]);


         $success['marketers']=New MarketerResource($marketer);

        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف المسوق  بنجاح','Marketers deleted successfully');

    }
     public function deleteall(Request $request)
    {
      $marketers =Marketer::whereIn('id',$request->id)->get();
      $marketers_id =Marketer::whereIn('id',$request->id)->pluck('user_id')->toArray();
            $users =User::whereIn('id',$marketers_id)->where('is_deleted',0)->get();
            if(count($users)>0){
           foreach($users as $user)
           {
             
             $user->update(['is_deleted' => 1]);
          

            }
            $success['marketers']=MarketerResource::collection($marketers);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف المندوب بنجاح','marketer deleted successfully');
    }
    else{
        $success['status']= 200;
    return $this->sendResponse($success,'المدخلات غير صحيحة','id does not exit');
    }
    }
  }
