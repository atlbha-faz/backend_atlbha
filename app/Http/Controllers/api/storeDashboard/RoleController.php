<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        if ($request->has('page')) {

            $roles = RoleResource::collection(Role::where('type', 'store')->whereNot('name', 'المالك')->where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->paginate(10));
            $success['page_count'] = $roles->lastPage();
            $pageNumber = request()->query('page', 1);
            $success['current_page'] = $roles->currentPage();
            $success['roles'] = $roles;
        } else {
            $success['roles'] = RoleResource::collection(Role::where('type', 'store')->whereNot('name', 'المالك')->where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->get());
        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الأدوار بنجاح', 'Roles shown successfully');
    }

    public function show($role)
    {
        $role = Role::query()->find($role);
        if (is_null($role) || $role->type != 'store' || $role->name == 'المالك' || $role->store_id != auth()->user()->store_id) {
            return $this->sendError("الدور غير موجود", "Role is't exists");
        }
        $success['role'] = new RoleResource($role);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الدور بنجاح', 'Role showed successfully');
    }

    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'role_name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where(function ($query) {
                return $query->where('store_id', auth()->user()->store_id);
            }),
            ],
            'permissions' => 'required|array',
            'permissions.*' => 'nullable|exists:permissions,id',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        $role = Role::create(['name' => $request->role_name, 'type' => 'store', 'guard_name' => 'api', 'store_id' => auth()->user()->store_id]);

        $role->syncPermissions($request->permissions);

        $success['role'] = new RoleResource($role);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الأدوار بنجاح', 'Role Added successfully');
    }

    public function update(Request $request, Role $role)
    {
        if (is_null($role) || $role->type != 'store' || $role->name == 'المالك' || $role->store_id != auth()->user()->store_id) {
            return $this->sendError("الدور غير موجود", " Role is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'role_name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where(function ($query) use ($role) {
                return $query->where('store_id', auth()->user()->store_id)->where('id', '!=', $role->id);
            })],
            'permissions' => 'required|array',
            'permissions.*' => 'nullable|exists:permissions,id',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $role->update(['name' => $request->role_name]);

        $role->syncPermissions($request->permissions);

        $success['role'] = new RoleResource($role);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم التعديل بنجاح', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if (is_null($role) || $role->type != 'store' || $role->name == 'المالك' || $role->store_id != auth()->user()->store_id) {
            return $this->sendError("الدور غير موجود", " Role is't exists");
        }

        $item = Role::withCount(['users' => function ($query) {
            $query->where('is_deleted', 0)->where('store_id', auth()->user()->store_id);
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
