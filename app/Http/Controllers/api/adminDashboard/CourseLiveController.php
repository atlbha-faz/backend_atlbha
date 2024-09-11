<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Unit;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\UnitResource;
use App\Http\Resources\VideoResource;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CourseLiveRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class CourseLiveController extends BaseController
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
        $data = Course::with(['user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->where('tags', null)->orderByDesc('created_at');
        $data = $data->paginate($count);
        $success['courses'] = CourseResource::collection($data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الكورسات المباشره بنجاح', 'courses return successfully');
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
    public function store(CourseLiveRequest $request)
    {
        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'user_id' => auth()->user()->id,
            'link' => $request->link,
        ]);
  
        // return new CountryResource($country);
        $success['courses'] = new CourseResource($course);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة كورس بنجاح', 'course Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($course)
    {
        $course = Course::where('id', $course)->first();
        if (is_null($course) || $course->is_deleted != 0) {
            return $this->sendError("الكورس غير موجودة", "course is't exists");
        }

        $success['course'] = new CourseResource($course);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بنجاح', 'course showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseLiveRequest $request, $course)
    {
        $course = Course::where('id', $course)->first();

        if (is_null($course) || $course->is_deleted != 0) {
            return $this->sendError("الكورس غير موجودة", "course is't exists");
        }
        $course->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $request->image, 
            'link' => $request->input('link'),
        ]);
        
        $success['courses'] = new CourseResource($course);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($course)
    {
        $course = Course::where('id', $course)->first();
        if (is_null($course) || $course->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "course is't exists");
        }
        $course->update(['is_deleted' => $course->id]);

        $success['course'] = new CourseResource($course);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الكورس بنجاح', 'course deleted successfully');
    }

}
