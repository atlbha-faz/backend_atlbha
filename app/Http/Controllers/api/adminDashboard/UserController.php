<?php

namespace App\Http\Controllers\api\adminDashboard;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;
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
    public function index(Request $request)
    {
        $userAdmain = User::where('user_type', 'admin')->first();
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data=User::where('is_deleted', 0)->where('user_type', 'admin_employee')->whereNot('id', auth()->user()->id)->whereNot('id', $userAdmain->id)->orderByDesc('created_at');
        if ($request->has('id')) {
            $data = $data->whereHas('roles' ,function ($userQuery) use ($request) {
                $userQuery->where('id', $request->id);
            });
        }
        $data= $data->paginate($count);
        $success['users'] = UserResource::collection( $data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
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
    public function store(UserRequest $request)
    {
  
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
    public function update(UserUpdateRequest $request, $id)
    {
        $userAdmain = User::where('user_type', 'admin')->first();
        $user = User::query()->whereNot('id', $userAdmain->id)->find($id);
        if (is_null($user) || $user->is_deleted != 0 || $user->user_type != 'admin_employee' || $user->id == auth()->user()->id) {
            return $this->sendError("المستخدم غير موجودة", "user is't exists");
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
    public function deleteAll(Request $request)
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
    public function changeSatusAll(Request $request)
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

  public function searchUserName(Request $request)
    {
        $userAdmain = User::where('user_type', 'admin')->first();
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $query = $request->input('query');
        $users = User::where('is_deleted', 0)->where('user_type', 'admin_employee')->whereNot('id', auth()->user()->id)->whereNot('id', $userAdmain->id)
        ->where(function ($q) use($query) {
           $q->where('user_name', 'like', "%$query%")
        ->orwhere('name', 'like', "%$query%");
        })->orderByDesc('created_at');
        $users=$users->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $users->total();
        $success['page_count'] = $users->lastPage();
        $success['current_page'] = $users->currentPage();
        $success['users'] = UserResource::collection($users);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المستخدمين بنجاح', 'users Information returned successfully');

    }

}
