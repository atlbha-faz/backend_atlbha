<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PermissionResource;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {

        $success['permissions'] = PermissionResource::collection(Permission::where('type', 'store')->where('parent_id', null)->get());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الصلاحيات بنجاح', 'Permission shown successfully');
    }

}
