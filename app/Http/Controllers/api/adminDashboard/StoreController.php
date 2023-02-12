<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Note;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Events\VerificationEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\NoteResource;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verificationNotification;
use App\Http\Controllers\api\BaseController as BaseController;

class StoreController extends BaseController
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

        $success['stores']=StoreResource::collection(Store::where('is_deleted',0,)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');
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
            'user_name'=>'required|string|max:255',
            'store_name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'store_email'=>'required|email|unique:stores',
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'domain'=>'required|url',
            'userphonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'phonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'activity_id' =>'required|array',
            'package_id' =>'required',
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'user_country_id'=>'required|exists:countries,id',
            'user_city_id'=>'required|exists:cities,id',
            'periodtype'=>'required|in:6months,year',
            'status'=>'required|in:active,inactive',
            'image'=>['image','mimes:jpeg,png,jpg,gif,svg','max:2048'],


        ]);

        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'user_name' => $request->user_name,
            'user_type' => "store",
            'password'=>$request->password,
            'phonenumber' => $request->userphonenumber,
            'image' => $request->image,
            'country_id' =>$request->user_country_id,
            'city_id' =>$request->user_city_id,
            'status' =>$request->status,
          ]);

          $userid =$user->id;


        $store = Store::create([
            'store_name' => $request->store_name,
            'store_email'=>$request->store_email,
            'domain' =>$request->domain,
            'icon' => $request->icon,
            'phonenumber' => $request->phonenumber,
            'description' =>$request->description,
            'business_license' => $request->business_license,
            'ID_file' => $request->ID_file,
            'snapchat' =>$request->snapchat,
            'facebook' => $request->facebook,
             'snapchat' =>$request->snapchat,
            'twiter' => $request->twiter,
              'youtube' => $request->youtube,
             'instegram' =>$request->instegram,
            'logo' => $request->logo,
            'entity_type' => $request->entity_type,
            'package_id' => $request->package_id,
            'user_id' => $userid,
            'periodtype'=>$request->periodtype,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id
          ]);

          $user->update([
               'store_id' =>  $store->id]);

         if($request->periodtype =="6months"){
           $end_at = date('Y-m-d',strtotime("+ 6 months", strtotime($store->created_at)));
         $store->update([
               'start_at'=> $store->created_at,
                'end_at'=>  $end_at ]);
        }
         else{
               $end_at = date('Y-m-d',strtotime("+ 1 years", strtotime($store->created_at)));
        $store->update([
               'start_at'=> $store->created_at,
                'end_at'=>  $end_at ]);

            }
          $store->activities()->attach($request->activity_id);
          $store->packages()->attach( $request->package_id,['start_at'=> $store->created_at,'end_at'=>$end_at,'periodtype'=>$request->periodtype,'packagecoupon_id'=>$request->packagecoupon]);


         $success['stors']=New StoreResource($store);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة متجر بنجاح',' store Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($store)
    {
        $store =Store::query()->find($store);
        if (is_null($store) || $store->is_deleted==1){
        return $this->sendError("المتجر غير موجودة","store is't exists");
        }


       $success['stores']=New StoreResource($store);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض المتجر  بنجاح','store showed successfully');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {

                $user =$store->user;


        if ($store->is_deleted==1){
            return $this->sendError("المتجر غير موجود","store is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'user_name'=>'required|string|max:255',
            'store_name'=>'required|string|max:255',
            'email'=>'required|email',
            'store_email'=>'required|email',
             'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'domain'=>'required|url',
            'userphonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'phonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'package_id' =>'required',
             'activity_id' =>'required|array',
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'user_country_id'=>'required|exists:countries,id',
            'user_city_id'=>'required|exists:cities,id',
            'periodtype'=>'required|in:6months,year',
           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }

            $user->update([
               'name' => $request->input('name'),
               'email' => $request->input('email'),
               'user_name' => $request->input('user_name'),
               'password' => $request->input('password'),
                'phonenumber' => $request->input('phonenumber'),
               'image' => $request->input('image'),
                'country_id' => $request->input('user_country_id'),
               'city_id' => $request->input('user_city_id'),
           ]);

           $store->update([
               'store_name' => $request->input('store_name'),
               'store_email' => $request->input('store_email'),
               'domain' => $request->input('domain'),
               'icon' => $request->input('icon'),
               'description' => $request->input('description'),
               'business_license' => $request->input('business_license'),
               'ID_file' => $request->input('ID_file'),
               'snapchat' => $request->input('snapchat'),
               'facebook' => $request->input('facebook'),
               'snapchat' => $request->input('snapchat'),
               'twiter' => $request->input('twiter'),
               'youtube' => $request->input('youtube'),
               'instegram' => $request->input('instegram'),
               'logo' => $request->input('logo'),
               'entity_type' => $request->input('entity_type'),
               'package_id' => $request->input('package_id'),
               'country_id' => $request->input('country_id'),
               'city_id' => $request->input('city_id'),
               'periodtype' => $request->input('periodtype'),
           ]);
             $store->activities()->sync($request->activity_id);

             if($request->periodtype =="6months"){
           $end_at = date('Y-m-d',strtotime("+ 6 months", strtotime($store->created_at)));

                $store->update([
               'start_at'=> $store->created_at,
                'end_at'=>  $end_at ]);

        }
          else{
               $end_at = date('Y-m-d',strtotime("+1 years", strtotime($store->created_at)));
              $store->update([
               'start_at'=> $store->created_at,
                'end_at'=>  $end_at ]);
            }
           $store->packages()->sync($request->package_id,['start_at'=>$store->created_at,'end_at'=>$end_at,'periodtype'=>$request->periodtype,'packagecoupon_id'=>$request->packagecoupon]);

           $success['stores']=New StoreResource($store);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','store updated successfully');
    }

     public function changeStatus(Request $request)
    {
         
          $stores =Store::whereIn('id',$request->id)->get();
           foreach($stores as $store)
           {
             if (is_null($store) || $store->is_deleted==1){
                   return $this->sendError("المتجر غير موجود"," Store is't exists");
       }
               
        if($store->status === 'active'){
        $store->update(['status' => 'not_active']);
        }
        else{
        $store->update(['status' => 'active']);
        }

            }
         
       
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة المتجر بنجاح','store updated successfully');

    }


     

      public function specialStatus($id)
    {
        $store = Store::query()->find($id);
         if (is_null($store) || $store->is_deleted==1){
         return $this->sendError("المتجر غير موجود","store is't exists");
         }

       if($store->special === 'not_special'){
        $store->update(['special' => 'special']);
        }
        else{
        $store->update(['special' => 'not_special']);
        }
        $success['store']=New StoreResource($store);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة المتجر بنجاح','store updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */



      public function changeSatusall(Request $request)
    {

            $stores =Store::whereIn('id',$request->id)->get();
           foreach($stores as $store)
           {
             if (is_null($store) || $store->is_deleted==1){
                   return $this->sendError("المتجر غير موجودة"," store is't exists");
       }
              if($store->status === 'active'){
        $store->update(['status' => 'not_active']);
        }
        else{
        $store->update(['status' => 'active']);
        }
        $success['stores']= New StoreResource($store);

            }
               $success['status']= 200;
                return $this->sendResponse($success,'تم تعطيل المتجر بنجاح','store stop successfully');
    }


    public function destroy($store)
    {
       $store = Store::query()->find($store);
         if (is_null($store) || $store->is_deleted==1){
         return $this->sendError("المتجر غير موجود","store is't exists");
         }
        $store->update(['is_deleted' => 1]);

        $success['store']=New StoreResource($store);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف المتجر بنجاح','store deleted successfully');
    }

    public function addNote(Request $request)
     {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'subject'=>'required|string|max:255',
            'details'=>'required|string',
            'store_id'=>'required'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $note = Note::create([
            'subject' => $request->subject,
            'details' => $request->details,
            'store_id' => $request->store_id,
            'product_id'=>null
          ]);


         $success['notes']=New NoteResource($note );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة ملاحظة بنجاح','note Added successfully');
    }

    public function verification_update(Request $request)
    {
      $store = Store::query()->find($request->store_id);
        $input = $request->all();
        $validator =  Validator::make($input ,[
           'activity_id' =>'required|array',
           'store_name'=>'required|string',
           'link'=>'required|url',
           'file'=>'required|mimes:pdf,doc,excel',
           'name'=>'required|string|max:255',
           'store_id'=>'required'
        ]);
        if ($validator->fails())
        {
           # code...
           return $this->sendError(null,$validator->errors());
        }

            $users = User::where('store_id', $request->store_id)->where('user_type','store')->get();
  
            $data = [
                'message' => 'تعديل توثيق',
                'store_id' =>  $request->store_id,
                'user_id'=>auth()->user()->id,
                'type'=>"store_verified",
                'object_id'=> $request->store_id
            ];
            foreach($users as $user)
            {
            Notification::send($user, new verificationNotification($data));
            }
            event(new VerificationEvent($data));
        $store->update([
            'store_name' =>  $request->input('store_name'),
            'link' =>  $request->input('link'),
            'file' =>  $request->input('file'),
            'confirmation_status'=>"accept"

        ]);

         $store->activities()->sync($request->activity_id);
       $user = User::where('is_deleted',0)->where('store_id',$request->store_id)->where('user_type','store')->first();
       $user->update([
         'name' =>  $request->input('name'),
         ]);
         

        $success['store']=Store::where('is_deleted',0)->where('id',$request->store_id)->first();
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل المتجر بنجاح','store update successfully');
    }
    public function deleteall(Request $request)
    {

            $stores =Store::whereIn('id',$request->id)->where('for','store')->get();
           foreach($stores as $store)
           {
            if (is_null($store) || $store->is_deleted==1){
                   return $this->sendError("المتجر غير موجودة"," store is't exists");
             }
               $store->update(['is_deleted' => 1]);
            }
               $success['stores']= StoreResource::collection($stores);
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف المتجر بنجاح','store deleted successfully');
    }

}
