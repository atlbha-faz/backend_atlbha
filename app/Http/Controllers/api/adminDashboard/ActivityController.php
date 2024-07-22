<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ActivityController extends BaseController
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
        $data=Activity::where('is_deleted', 0)->orderByDesc('created_at');
        $data= $data->paginate($count);
        $success['activities'] = ActivityResource::collection($data);
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع الانشطة بنجاح', 'Activities return successfully');
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
            'name' => ['required', 'string', 'max:255', Rule::unique('activities')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })],
            'icon' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $activity = Activity::create([
            'name' => $request->name,
            'icon' => $request->icon,

        ]);

        $success['activities'] = new ActivityResource($activity);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة النشاط بنجاح', 'Activity Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $activity)
    {
        $activity = Activity::where('id', $activity)->first();

        if (is_null($activity) || $activity->is_deleted != 0) {
            return $this->sendError("النشاط غير موجودة", " Activity is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', Rule::unique('activities')->where(function ($query) use ($activity) {
                return $query->where('is_deleted', 0)->where('id', '!=', $activity->id);
            })],
            'icon' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $activity->update([
            'name' => $request->input('name'),
            'icon' => $request->icon,

        ]);

        $success['activities'] = new ActivityResource($activity);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'Activity updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {

        $activities = Activity::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($activities) > 0) {
            foreach ($activities as $activity) {

                $activity->update(['is_deleted' => $activity->id]);
                $success['activities'] = new ActivityResource($activity);

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف الأنشطة بنجاح', 'Activity deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }

    }
}
