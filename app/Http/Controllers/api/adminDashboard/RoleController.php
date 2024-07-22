<?php

namespace App\Http\Controllers\api\adminDashboard;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Requests\AdminRoleRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AdminRoleUpdateRequest;
use App\Http\Controllers\api\BaseController as BaseController;

class RoleController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {

        $success['roles'] = RoleResource::collection(Role::where('type', 'admin')->whereNot('id', 1)->orderByDesc('created_at')->get());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الأدوار بنجاح', 'Roles shown successfully');
    }

    public function show($role)
    {
        $role = Role::query()->find($role);
        if (is_null($role) || $role->type != 'admin' || $role->id == 1) {
            return $this->sendError("الدور غير موجود", "Role is't exists");
        }
        $success['role'] = new RoleResource($role);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الدور بنجاح', 'Role showed successfully');
    }

    public function store(AdminRoleRequest $request)
    {

        if (in_array(2, $request->permissions)) {
            $request->permissions = $request->permissions;
        } else {
            $request->permissions = array_merge($request->permissions, [2]);
        }

        $role = Role::create(['name' => $request->role_name, 'type' => 'admin', 'guard_name' => 'api']);

        $role->syncPermissions($request->permissions);

        $success['role'] = new RoleResource($role);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الأدوار بنجاح', 'Role Added successfully');
    }

    public function update(AdminRoleUpdateRequest $request, Role $role)
    {
        if (is_null($role) || $role->type != 'admin' || $role->id == 1) {
            return $this->sendError("الدور غير موجود", " Role is't exists");
        }
       

        if (in_array(2, $request->permissions)) {
            $request->permissions = $request->permissions;
        } else {
            $request->permissions = array_merge($request->permissions, [2]);
        }
        $role->update(['name' => $request->role_name]);

        $role->syncPermissions($request->permissions);

        $success['role'] = new RoleResource($role);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم التعديل بنجاح', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if (is_null($role) || $role->type != 'admin' || $role->id == 1) {
            return $this->sendError("الدور غير موجود", " Role is't exists");
        }

        $item = Role::withCount(['users' => function ($query) {
            $query->where('is_deleted', 0);
        }])->findOrFail($role->id);
        if ($item->users_count) {
            return $this->sendError('الصلاحية ممنوحة للموظف', 'Permission granted to the employee');
        }

        $role->revokePermissionTo($role->permissions);
        $role->delete();

        $success['role'] = new RoleResource($role);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم الحذف بنجاح', 'Role updated successfully');
    }

}
