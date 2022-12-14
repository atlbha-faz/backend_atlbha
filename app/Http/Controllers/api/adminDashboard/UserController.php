<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class UserController  extends BaseController
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
        $success['users']=UserResource::collection(User::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع االمستخدمين بنجاح','Users return successfully');
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
            'user_id'=>'required|max:255',
            'user_name'=>'required|string|max:255',
            'user_type'=>'required|in:admin,admin_employee,store,store_employee,customer',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'gender'=>'required|in:male,female',
            'phoneNumber'=>'required|numeric',
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $user = User::create([
            'name'=> $request->name,
            'user_id'=> $request->user_id,
            'user_name'=> $request->user_name,
            'user_type'=>$request->user_type,
            'email' => $request->email,
            'password' => $request->password,
            'gender' => $request->gender,
            'phoneNumber' => $request->phoneNumber,
             'image' => $request->image,
             'country_id' =>$request->country_id,
             'city_id' =>$request->city_id,
             
          ]);

         $success['activities']=New UserResource($user );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة المستخدم بنجاح','User Added successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::query()->find($id);
        if (is_null($user) || $user->is_deleted==1){
             return $this->sendError("المستخدم غير موجودة","user is't exists");
             }
            $success['users']=New UserResource($user);
            $success['status']= 200;
    
             return $this->sendResponse($success,'تم  عرض بنجاح','user showed successfully');
            }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user =User::query()->find($id);

        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'user_id'=>'required|max:255',
            'user_name'=>'required|string|max:255',
            'user_type'=>'required|in:admin,admin_employee,store,store_employee,customer',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'gender'=>'required|in:male,female',
            'phonenumber'=>'required|numeric',
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $user->update([
            'name'=> $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'gender' => $request->input('gender'),
            'phoneNumber' => $request->input('phoneNumber'),
      
             'image' => $request->input('image'),
             'country_id' =>$request->input('country_id'),
             'city_id' =>$request->input('city_id'),
             'socialmediatext' =>$request->input('socialmediatext')
        ]);

        $success['users']=New UserResource($user);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','modify  successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $user =User::query()->find($id);
        if (is_null($user)||$user->is_deleted==1){
            return $this->sendError("المستخدم غير موجودة","User is't exists");
            }
           $user->update(['is_deleted' => 1]);

           $success['useres']=New UserResource($user);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف المستخدم بنجاح','User deleted successfully');
        }
}