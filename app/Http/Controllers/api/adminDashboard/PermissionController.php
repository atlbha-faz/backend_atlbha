<?php

namespace App\Http\Controllers\api\adminDashboard;
use Notification;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class PermissionController extends BaseController
{

       public function __construct()
    {
        $this->middleware('auth:api');
    }
     public function index()
    {

        $success['permissions']=PermissionResource::collection(Permission::where('type','admin')->where('parent_id',null)->whereNotIn('id', [1,2])->get());

        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض الصلاحيات بنجاح','Permission shown successfully');
    }


   
}
