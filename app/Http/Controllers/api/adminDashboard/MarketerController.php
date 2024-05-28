<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\MarketerResource;
use App\Models\Marketer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MarketerController extends BaseController
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
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data = Marketer::whereHas('user', function ($q) {
            $q->where('is_deleted', 0);
        })->orderByDesc('created_at');
        $data = $data->paginate($count);
        $success['marketers'] = MarketerResource::collection($data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المندوبين بنجاح', 'marketer return successfully');

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
            'checkbox_field' => 'required|in:1',
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0);
            })],

            'password' => 'nullable',
            'password_confirm' => 'nullable',
            'user_name' => ' nullable',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0);
            }),
            ],
            'snapchat' => 'required|url',
            'facebook' => 'required|url',
            'twiter' => 'required|url',
            'whatsapp' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'youtube' => 'required|url',
            'instegram' => 'required|url',
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,not_active',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'gender' => $request->gender,
            'image' => $request->image,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'status' => $request->status,
            'user_type' => "marketer",
        ]);
        $marketer = Marketer::create([
            'user_id' => $user->id,
            'facebook' => $request->facebook,
            'snapchat' => $request->snapchat,
            'twiter' => $request->twiter,
            'whatsapp' => $request->whatsapp,
            'youtube' => $request->youtube,
            'instegram' => $request->instegram,

        ]);

        $success['marketers'] = new MarketerResource($marketer);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة مندوب بنجاح', 'marketer Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function show($marketer)
    {
        $marketer = Marketer::query()->find($marketer);
        if ($marketer != null) {
            $user = User::query()->find($marketer->user_id);
        }
        if (is_null($marketer) || is_null($user) || $user->is_deleted != 0 || $user->user_type != "marketer") {
            return $this->sendError("المندوب غير موجودة", "marketer is't exists");
        }
        $success['marketers'] = new MarketerResource($marketer);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'marketer showed successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function edit(Marketer $marketer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $marketer)
    {
        $marketer = Marketer::where('id', $marketer)->first();
        $user = User::query()->find($marketer->user_id);
        if (is_null($user) || $user->is_deleted != 0) {
            return $this->sendError(" المندوب غير موجود", "marketer is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($marketer) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0)->where('id', '!=', $marketer->user->id);
            }),
            ],

            'password' => 'nullable',
            'password_confirm' => 'nullable',
            'user_name' => ' nullable',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) use ($marketer) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0)->where('id', '!=', $marketer->user->id);
            }),
            ],
            'snapchat' => 'required',
            'facebook' => 'required',
            'twiter' => 'required',
            'whatsapp' => 'required',
            'youtube' => 'required',
            'instegram' => 'required',
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,not_active',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $user = User::query()->find($marketer->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'gender' => $request->gender,
            'image' => $request->image,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'status' => $request->status,
        ]);
        $marketer->update([
            'facebook' => $request->input('facebook'),
            'snapchat' => $request->input('snapchat'),
            'twiter' => $request->input('twiter'),
            'whatsapp' => $request->input('whatsapp'),
            'youtube' => $request->input('youtube'),
            'instegram' => $request->input('instegram'),
        ]);

        if (!is_null($request->password)) {
            $user->update([
                'password' => $request->password,
            ]);
        }

        $success['marketers'] = new MarketerResource($marketer);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'modify  successfully');

    }

    public function changeStatus($id)
    {
        $marketer = Marketer::query()->find($id);
        $user = User::query()->find($marketer->user_id);
        if (is_null($user) || $user->is_deleted != 0) {
            return $this->sendError("المندوب غير موجودة", "user is't exists");
        }
        if ($user->status === 'active') {
            $user->update(['status' => 'not_active']);
        } else {
            $user->update(['status' => 'active']);
        }
        $success['marketers'] = new MarketerResource($marketer);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعدبل حالة المندوب  بنجاح', 'marketer status updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marketer  $marketer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marketer $marketer)
    {
        $user = User::query()->find($marketer->user_id);
        if (is_null($user) || $user->is_deleted != 0) {
            return $this->sendError("المندوب غير موجودة", "user is't exists");
        }
        $user->update(['is_deleted' => $user->id]);

        $success['marketers'] = new MarketerResource($marketer);

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المسوق  بنجاح', 'Marketers deleted successfully');

    }
    public function deleteAll(Request $request)
    {
        $marketers = Marketer::whereIn('id', $request->id)->get();
        $marketers_id = Marketer::whereIn('id', $request->id)->pluck('user_id')->toArray();
        $users = User::whereIn('id', $marketers_id)->where('is_deleted', 0)->get();
        if (count($users) > 0) {
            foreach ($users as $user) {

                $user->update(['is_deleted' => $user->id]);

            }
            $success['marketers'] = MarketerResource::collection($marketers);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف المندوب بنجاح', 'marketer deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }
    public function searchMarketerName(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $query = $request->input('query');
        $users = Marketer::whereHas('user', function ($q) use ($query) {
            $q->where('is_deleted', 0)->where('name', 'like', "%$query%");
        })->orderByDesc('created_at');
        $users=$users->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $users->total();
        $success['page_count'] = $users->lastPage();
        $success['current_page'] = $users->currentPage();
        $success['marketers'] = MarketerResource::collection($users);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المندوبين بنجاح', 'marketers Information returned successfully');

    }
}
