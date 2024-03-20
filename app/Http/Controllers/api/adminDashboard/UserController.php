<?php

namespace App\Http\Controllers\api\adminDashboard;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class UserController extends BaseController
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
        $userAdmain = User::where('user_type', 'admin')->first();
        $success['users'] = UserResource::collection(User::where('is_deleted', 0)->where('user_type', 'admin_employee')->whereNot('id', auth()->user()->id)->whereNot('id', $userAdmain->id)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع االمستخدمين بنجاح', 'Users return successfully');
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
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' => ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])
                    ->where('is_deleted', 0);
            }),
            ],
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])
                    ->where('is_deleted', 0);
            }),
            ],
            'password' => 'required|min:8|string',
            'password_confirm' => 'required|same:password',
         
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])
                    ->where('is_deleted', 0);
            })],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
      
            'role' => 'required|string|max:255|exists:roles,name',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'user_type' => 'admin_employee',
            'email' => $request->email,
            'password' => $request->password,
            'phonenumber' => $request->phonenumber,
            'image' => $request->image,
            'verified' => 1,

        ]);

        $user->assignRole($request->role);

        $success['users'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة المستخدم بنجاح', 'User Added successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userAdmain = User::where('user_type', 'admin')->first();
        $user = User::query()->whereNot('id', $userAdmain->id)->find($id);
        if (is_null($user) || $user->is_deleted != 0 || $user->user_type != 'admin_employee' || $user->id == auth()->user()->id) {
            return $this->sendError("المستخدم غير موجودة", "user is't exists");
        }
        $success['users'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'user showed successfully');
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
        $userAdmain = User::where('user_type', 'admin')->first();
        $user = User::query()->whereNot('id', $userAdmain->id)->find($id);
        if (is_null($user) || $user->is_deleted != 0 || $user->user_type != 'admin_employee' || $user->id == auth()->user()->id) {
            return $this->sendError("المستخدم غير موجودة", "user is't exists");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' => ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])->where('id', '!=', $user->id)
                    ->where('is_deleted', 0);
            }),
            ],
            // 'email' => 'required|email|unique:users,email,' . $user->id,
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])->where('is_deleted', 0)
                    ->where('id', '!=', $user->id);
            }),
            ],
            'password' => 'nullable|min:8|string',
            'password_confirm' => 'nullable|same:password',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])->where('is_deleted', 0)
                    ->where('id', '!=', $user->id);
            }),
            ],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'role' => 'required|string|max:255|exists:roles,name',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_name' => $request->input('user_name'),
            'phonenumber' => $request->input('phonenumber'),
            'image' => $request->image,

        ]);
        if (!is_null($request->password)) {
            $user->update([
                'password' => $request->password,
            ]);
        }

        $user->assignRole($request->role);

        $success['users'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'modify  successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userAdmain = User::where('user_type', 'admin')->first();
        $user = User::query()->whereNot('id', $userAdmain->id)->find($id);
        if (is_null($user) || $user->is_deleted != 0 || $user->user_type != 'admin_employee' || $user->id == auth()->user()->id) {
            return $this->sendError("المستخدم غير موجودة", "User is't exists");
        }
        $user->update(['is_deleted' => $user->id]);

        $success['useres'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المستخدم بنجاح', 'User deleted successfully');
    }
    public function deleteall(Request $request)
    {
        $userAdmain = User::where('user_type', 'admin')->first();
        $users = User::whereIn('id', $request->id)->whereNot('id', $userAdmain->id)->whereNot('id', auth()->user()->id)->where('is_deleted', 0)->where('user_type', 'admin_employee')->get();
        if (count($users) > 0) {
            foreach ($users as $user) {

                $user->update(['is_deleted' => $user->id]);
                $success['users'] = new UserResource($user);

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف المستخدم بنجاح', 'user deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }
    }
    public function changeSatusall(Request $request)
    {
        $userAdmain = User::where('user_type', 'admin')->first();
        $users = User::whereIn('id', $request->id)->whereNot('id', $userAdmain->id)->whereNot('id', auth()->user()->id)->where('is_deleted', 0)->where('user_type', 'admin_employee')->get();
        if (count($users) > 0) {
            foreach ($users as $user) {
                if ($user->status === 'active') {
                    $user->update(['status' => 'not_active']);
                } else {
                    $user->update(['status' => 'active']);
                }
                $success['users'] = new UserResource($user);

            }
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل حالة المستخدم بنجاح', 'user updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }
    }
 

}
