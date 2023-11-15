<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $success['users'] = new UserResource(auth()->user());
        $success['verification_count'] = Store::where('is_deleted', 0)->where('verification_status', '=', 'admin_waiting')->count();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'user showed successfully');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' => ['required','string','max:255',Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])->where('is_deleted',0)
                    ->where('id', '!=', $user->id);
            }),
            ],
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])->where('is_deleted',0)
                    ->where('id', '!=', $user->id);
            }),
            ],
            'password' => 'nullable|min:8|string',
            'confirm_password' => 'required_if:password,required|same:password',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])
                    ->where('id', '!=', $user->id)->where('is_deleted',0);
            }),
            ],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $user->update([
            'name' => $request->input('name'),
            'user_name' => $request->input('user_name'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'image' => $request->image,
        ]);
        if (!is_null($request->password)) {
            $user->update([
                'password' => $request->input('password'),
            ]);
        }

        $success['users'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'modify  successfully');

    }

}
