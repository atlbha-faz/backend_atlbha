<?php

namespace App\Http\Controllers\api\storeDashboard;
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

                $success['permissions']=PermissionResource::collection(Permission::where('type','store')->where('parent_id',null)->get());

        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض الصلاحيات بنجاح','Permission shown successfully');
    }


   
}
