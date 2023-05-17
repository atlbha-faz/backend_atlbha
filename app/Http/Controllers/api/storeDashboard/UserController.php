<?php

namespace App\Http\Controllers\api\storeDashboard;

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
        $success['users']=UserResource::collection(User::where('is_deleted',0)->where('store_id',auth()->user()->store_id)->get());
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
            'user_name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'status'=>'required|in:active,not_active',
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'phonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $user = User::create([
            'name'=> $request->name,
            'user_name'=> $request->user_name,
            'user_type'=>'store_employee',
            'email' => $request->email,
            'password' => $request->password,
            'phonenumber' => $request->phonenumber,
             'image' => $request->image,
             'status' => $request->status,
             'store_id' => auth()->user()->store_id

          ]);

         $success['users']=New UserResource($user );
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
        $user = User::where('id',$id)->where('store_id',auth()->user()->store_id)->first();
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
        $user =User::where('id',$id)->where('store_id',auth()->user()->store_id)->first();

        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'user_name'=>'required|string|max:255',
            'email'=>'required|email',
            'password'=>'nullable|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
             'password_confirm' => 'nullable|same:password',
            'status'=>'required|in:active,not_active',
            'phonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'image'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],

        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $user->update([
            'name'=> $request->input('name'),
            'user_name'=> $request->input('user_name'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'phonenumber' => $request->input('phonenumber'),
             'image' => $request->image,
          'store_id' => auth()->user()->store_id

        ]);
        
        if(!is_null($request->password)){
             $user->update([
            'password' => $request->input('password'),
                 ]);
        }

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
           $user = User::where('id',$id)->where('store_id',auth()->user()->store_id)->first();

        if (is_null($user)||$user->is_deleted==1){
            return $this->sendError("المستخدم غير موجودة","User is't exists");
            }
           $user->update(['is_deleted' => 1]);

           $success['useres']=New UserResource($user);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف المستخدم بنجاح','User deleted successfully');
        }
    public function deleteall(Request $request)
    {

            $users =User::whereIn('id',$request->id)->where('store_id',auth()->user()->store_id)->get();
           foreach($users as $user)
           {
             if (is_null($user) || $user->is_deleted==1 ){
                    return $this->sendError("المستخدم غير موجودة","user is't exists");
             }
             $user->update(['is_deleted' => 1]);
            $success['users']=New UserResource($user);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف المستخدم بنجاح','user deleted successfully');
    }
       public function changeSatusall(Request $request)
      {

               $users =User::whereIn('id',$request->id)->where('store_id',auth()->user()->store_id)->get();
                foreach($users as $user)
                {
                    if (is_null($user) || $user->is_deleted==1){
                        return $this->sendError("  المستخدم غير موجودة","user is't exists");
              }
                    if($user->status === 'active'){
                $user->update(['status' => 'not_active']);
                }
                else{
                $user->update(['status' => 'active']);
                }
                $success['users']= New UserResource($user);

                    }
                    $success['status']= 200;

                return $this->sendResponse($success,'تم تعديل حالة المستخدم بنجاح','user updated successfully');
     }
  public function changeStatus($id)
    {
        $user = User::where('id',$id)->where('store_id',auth()->user()->store_id)->first();
        if (is_null($user) || $user->is_deleted==1 ){
         return $this->sendError("المستخدم غير موجودة","user is't exists");
         }
        if($user->status === 'active'){
            $user->update(['status' => 'not_active']);
     }
    else{
        $user->update(['status' => 'active']);
    }
        $success['users']=New UserResource($user);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة المستخدم بنجاح',' user status updared successfully');

    }

}
