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


   
}
