<?php

namespace App\Http\Controllers\api\storeDashboard;

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
        $success['users'] = UserResource::collection(User::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());
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
            'user_name' => 'required|string|max:255',
           'email' => ['required','email', Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type', 'store_employee');
                }),
                ],
            'status' => 'required|in:active,not_active',
            'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                return $query->where('user_type', 'store_employee');
            })],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'role' => 'required|string|max:255|exists:roles,name',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'user_type' => 'store_employee',
            'email' => $request->email,
            'password' => $request->password,
            'phonenumber' => $request->phonenumber,
            'image' => $request->image,
            'status' => $request->status,
            'store_id' => auth()->user()->store_id,

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
        $user = User::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
        if (is_null($user) || $user->is_deleted == 1) {
            return $this->sendError("المستخدم غير موجود", "user is't exists");
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
        $user = User::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
if (is_null($user) || $user->is_deleted == 1 ) {
    return $this->sendError("المستخدم غير موجود", "user is't exists");
}

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['store_employee','store'])
                    ->where('id', '!=', $user->id);
            }),
            ],
            'password' => 'nullable|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'password_confirm' => 'nullable|same:password',
            'status' => 'required|in:active,not_active',
             'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['store_employee','store'])
                    ->where('id', '!=', $user->id);
            }),
            ],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'role' => 'required|string|max:255|exists:roles,name',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $user->update([
            'name' => $request->input('name'),
            'user_name' => $request->input('user_name'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'phonenumber' => $request->input('phonenumber'),
            'image' => $request->image,
            'store_id' => auth()->user()->store_id,

        ]);

        if (!is_null($request->password)) {
            $user->update([
                'password' => $request->input('password'),
            ]);
        }

        $user->assignRole($request->role);
        $user->save();
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
        $user = User::where('id', $id)->where('store_id', auth()->user()->store_id)->first();

        if (is_null($user) || $user->is_deleted == 1) {
            return $this->sendError("المستخدم غير موجودة", "User is't exists");
        }
        $user->update(['is_deleted' => 1]);

        $success['useres'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المستخدم بنجاح', 'User deleted successfully');
    }
    public function deleteall(Request $request)
    {

        $users = User::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->update(['is_deleted' => 1]);
                $success['users'] = new UserResource($user);
            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف المستخدم بنجاح', 'user deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendError("المستخدم غير موجودة", "user is't exists");
        }
    }
    public function changeSatusall(Request $request)
    {

        $users = User::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
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
            return $this->sendError("المستخدم غير موجودة", "user is't exists");
        }
    }
    public function changeStatus($id)
    {
        $user = User::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
        if (is_null($user) || $user->is_deleted == 1) {
            return $this->sendError("المستخدم غير موجودة", "user is't exists");
        }
        if ($user->status === 'active') {
            $user->update(['status' => 'not_active']);
        } else {
            $user->update(['status' => 'active']);
        }
        $success['users'] = new UserResource($user);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعدبل حالة المستخدم بنجاح', ' user status updared successfully');

    }

}
