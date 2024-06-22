<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AdminProfileRequest;
use App\Http\Controllers\api\BaseController as BaseController;

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

    public function update(AdminProfileRequest $request)
    {
        $user = auth()->user();

        $input = $request->all();
      
        $user->update([
            'name' => $request->input('name'),
            'user_name' => $request->input('user_name'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'image' => $request->image,
        ]);
        if ($request->has('password')) {
            $user->update([
                'password' => $request->input('password'),
            ]);
        }

        $success['users'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'modify  successfully');

    }

}
