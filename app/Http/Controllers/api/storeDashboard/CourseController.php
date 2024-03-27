<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends BaseController
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
        $courses = CourseResource::collection(Course::where('is_deleted', 0)->orderByDesc('created_at')->paginate($count));
        $success['page_count'] = $courses->lastPage();
        $success['current_page'] = $courses->currentPage();
        $success['courses'] = $courses;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الكورسات المشروحة بنجاح', 'courses return successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($course)
    {
        $course = Course::query()->find($course);
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
    public function searchCourseName(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $courses = Course::where('is_deleted', 0)
            ->where('name', 'like', "%$query%")->orderBy('created_at', 'desc')
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $courses->total();
        $success['page_count'] = $courses->lastPage();
        $success['current_page'] = $courses->currentPage();
        $success['pages'] = CourseResource::collection($courses);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الكورسات بنجاح', 'courses Information returned successfully');

    }

}
