<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
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

        $success['stores']=StoreResource::collection(Store::where('is_deleted',0)->get());
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
            'store_name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'store_email'=>'required|email|unique:stores',
            'password'=>'required',
            'domain'=>'required|url',
            'icon' =>'required',
            'phonenumber' =>'required|numeric',
            'description' =>'required',
            'business_license' =>'required|mimes:jpeg,png,jpg,gif,svg,pdf','max:2048',
            'ID_file' =>'required|mimes:jpeg,png,jpg,gif,svg,pdf','max:2048',
            'accept_status' =>'required|in:pending,accepted,rejected',
            'snapchat' =>'required|url',
            'facebook' =>'required|url',
            'twiter' =>'required|url',
            'youtube' =>'required|url',
            'instegram' =>'required|url',
            'logo' =>'required|mimes:jpeg,png,jpg,gif,svg,pdf','max:2048',
            'entity_type' =>'required',
            'activity_id' =>'required|exists:activities,id',
            'package_id' =>'required|exists:packages,id',
            'start_at'=>'required|date',
            'end_at'=>'required|date',
            'period'=>'required|numeric',
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'start_at'=>'required|date',
            'end_at'=>'required|date',
            'period'=>'required|numeric',

        ]);

        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'user_id' =>$request->user_id,
            'user_name' => $request->user_name,
            'user_type' => "store",
            'password'=>$request->password,
            'gender' =>$request->gender,
            'phonenumber' => $request->phonenumber,
            'image' => $request->image,
            'country_id' =>$request->country_id,
            'city_id' =>$request->city_id,
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
             'activity_id' =>$request->activity_id,
            'package_id' => $request->package_id,
            'user_id' => $userid,
            'start_at'=>$request->start_at,
            'end_at'=>$request->end_at,
            'period'=>$request->period,
            'accept_status' => $request->accept_status,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
             'start_at'=>$request->start_at,
            'end_at'=>$request->end_at,
            'period'=>$request->period,

          ]);
        //    dd($store->id);
          $user->update([
               'store_id' =>  $store->id]);

          $store->packages()->attach(explode(',', $request->package_id),['start_at'=>$request->start_at,'end_at'=>$request->end_at,'period'=>$request->period,'packagecoupon'=>$request->packagecoupon]);


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

    public function rateing($store)
    {
      $products=Product::where('store_id',$store)->get();
      $rating=$products->comment->avg('rateing');

       // $rating =$product->comment->avg('rateing');

        $success['rateing']= $rating;
        $success['status']= 200;
         return $this->sendResponse($success,'تم عرض التقييم بنجاح',' rateing showrd successfully');

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
            'store_name'=>'required|string|max:255',
            'email'=>'required|email',
            'store_email'=>'required|email',
            'password'=>'required',
            'domain'=>'required|url',
            'icon' =>'required',
            'phonenumber' =>'required|numeric',
            'description' =>'required',
            'business_license' =>'required|mimes:jpeg,png,jpg,gif,svg,pdf','max:2048',
            'ID_file' =>'required|mimes:jpeg,png,jpg,gif,svg,pdf','max:2048',
            'accept_status' =>'required|in:pending,accepted,rejected',
            'snapchat' =>'required|url',
            'facebook' =>'required|url',
            'twiter' =>'required|url',
            'youtube' =>'required|url',
            'instegram' =>'required|url',
            'logo' =>'required|mimes:jpeg,png,jpg,gif,svg,pdf','max:2048',
            'entity_type' =>'required',
            'activity_id' =>'required|exists:activities,id',
            'package_id' =>'required|exists:packages,id',
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
            'start_at'=>'required|date',
            'end_at'=>'required|date',
            'period'=>'required|numeric',

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }

            $user->update([
               'name' => $request->input('name'),
               'email' => $request->input('email'),
               'user_id' => $request->input('user_id'),
               'user_name' => $request->input('user_name'),
               'password' => $request->input('password'),
               'gender' => $request->input('gender'),
                'phonenumber' => $request->input('phonenumber'),
               'image' => $request->input('image'),
                'country_id' => $request->input('country_id'),
               'city_id' => $request->input('city_id'),
           ]);

           $store->update([
               'store_name' => $request->input('store_name'),
               'store_email' => $request->input('store_email'),
               'domain' => $request->input('domain'),
               'icon' => $request->input('icon'),
                 'phonenumber' => $request->input('phonenumber'),
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
               'activity_id' => $request->input('activity_id'),
               'package_id' => $request->input('package_id'),
               'accept_status' => $request->input('accept_status'),
               'country_id' => $request->input('country_id'),
               'city_id' => $request->input('city_id'),
                'start_at' => $request->input('start_at'),
               'end_at' => $request->input('end_at'),
               'end_at' => $request->input('end_at'),
               'period' => $request->input('period'),
           ]);
           $store->packages()->sync(explode(',', $request->package_id),['start_at'=>$request->start_at,'end_at'=>$request->end_at,'period'=>$request->period,'packagecoupon'=>$request->packagecoupon]);

           $success['stores']=New StoreResource($store);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','store updated successfully');
    }

     public function changeStatus($id)
    {
        $store = Store::query()->find($id);
         if (is_null($store) || $store->is_deleted==1){
         return $this->sendError("المتجر غير موجود","store is't exists");
         }

        if($store->status === 'active'){
        $store->update(['status' => 'not_active']);
        }
        else{
        $store->update(['status' => 'active']);
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
}