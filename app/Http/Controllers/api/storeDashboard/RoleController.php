<?php

namespace App\Http\Controllers\api\storeDashboard;
use Notification;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class RoleController extends BaseController
{

       public function __construct()
    {
        $this->middleware('auth:api');
    }
     public function index()
    {

                $success['roles']=RoleResource::collection(Role::where('type','store')->get());

        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض الأدوار بنجاح','Roles shown successfully');
    }
    
    
  public function store(Request $request)
  {
      
      
      $input = $request->all();
            $validator =  Validator::make($input ,[
                 'role_name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'required|array',
        'permissions.*' => 'nullable|in:permissions,id',
             
            ]);
            if ($validator->fails())
            {
                return $this->sendError(null,$validator->errors());
            }
      
      
    $role = Role::create(['name'=>$request->role_name , 'type'=>'store']);
      
    $role->syncPermissions($request->permissions);

    $success['role']=New RoleResource($role);
    $success['status']= 200;

     return $this->sendResponse($success,'تم إضافة الأدوار بنجاح','Role Added successfully');
  }


   
}
