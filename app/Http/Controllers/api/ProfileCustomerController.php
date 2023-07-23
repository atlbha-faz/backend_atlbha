<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ProfileCustomerController  extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
        $success['users']=new UserResource(auth()->user());
        $success['status']= 200;

         return $this->sendResponse($success,'تم  عرض بنجاح','user showed successfully');
    }


    public function update(Request $request)
    {
        $user =auth()->user();

        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'user_name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$user->id,
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
            'phonenumber' => $request->input('phonenumber'),
             'image' => $request->image,
        ]);

        $success['users']=New UserResource($user);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','modify  successfully');

    }


}
