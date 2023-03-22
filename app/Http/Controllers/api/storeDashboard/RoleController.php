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
    
    public function show($role)
    {
        $role= Role::query()->find($role);
        if ( is_null($role) || $role->type!='store'){
               return $this->sendError("الدور غير موجود","Role is't exists");
               }
              $success['role']=New RoleResource($role);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الدور بنجاح','Role showed successfully');
    }

    
    
  public function store(Request $request)
  {
      
      
      $input = $request->all();
            $validator =  Validator::make($input ,[
                 'role_name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'required|array',
        'permissions.*' => 'nullable|exists:permissions,id',
             
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
    
    
     public function update(Request $request, Role $role)
    {
        if (is_null($role) ||  $category->type!= 'store'){
            return $this->sendError("الدور غير موجود"," Role is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                 'role_name' => 'required|string|max:255|unique:roles,name,'$role->id,
        'permissions' => 'required|array',
        'permissions.*' => 'nullable|exists:permissions,id',

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
        
         
         
      
    $role = Role::create(['name'=>$request->role_name , 'type'=>'store']);
      
    $role->syncPermissions($request->permissions);
         
    $success['role']=New RoleResource($role);
    $success['status']= 200;
            return $this->sendResponse($success,'تم التعديل بنجاح','Role updated successfully');
    }

    
       public function delete( Role $role)
    {
        if (is_null($role) ||  $category->type!= 'store'){
            return $this->sendError("الدور غير موجود"," Role is't exists");
       }
           
    $item = Role::withCount('users')->findOrFail($role->id);
    if ($item->users_count) {
      return $this->sendError('Permission granted to the employee', 'الصلاحية ممنوحة للموظف');
    }
      
    $role->revokePermissionTo($role->permissions);
    $role->delete();
           
    $success['role']=New RoleResource($role);
    $success['status']= 200;
            return $this->sendResponse($success,'تم الحذف بنجاح','Role updated successfully');
    }


   
}
