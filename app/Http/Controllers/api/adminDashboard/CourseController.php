<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CourseResource;
use App\Http\Controllers\api\BaseController as BaseController;
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
    public function index()
    {

       $success['courses']=CourseResource::collection(Course::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الكورسات المشروحة بنجاح','courses return successfully');
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
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'description'=>'required|string',
            'duration' =>'required',
            'user_id'=>'required|exists:users,id'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $course = Course::create([
            'name' => $request->name,
            'description'=>$request->description,
            'duration' =>$request->duration,
            'tags' =>implode(',', $request->tags),
             'user_id' => $request->user_id,
          ]);

         // return new CountryResource($country);
         $success['courses']=New CourseResource($course);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة كورس بنجاح','course Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($course)
    {
          $course = Course::query()->find($course);
         if (is_null($course ) || $course->is_deleted == 1){
         return $this->sendError("الكورس غير موجودة","course is't exists");
         }


        $success['$courses']=New CourseResource($course);
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض بنجاح','course showed successfully');
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
    public function update(Request $request, Course $course)
    {
        if (is_null($course ) || $course->is_deleted==1){
         return $this->sendError("الكورس غير موجودة","course is't exists");
        }
         $input = $request->all();
        $validator =  Validator::make($input ,[
           'name'=>'required|string|max:255',
            'description'=>'required|string',
            'duration' =>'required',
             'user_id'=>'required|exists:users,id'
        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $course->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'description' => $request->input('description'),
            'tags' =>implode(',',$request->input('tags')),
            'user_id' => $request->input('user_id')
        ]);
       //$country->fill($request->post())->update();
        $success['courses']=New CourseResource($course);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','course updated successfully');
}

   public function changeStatus($id)
    {
        $course = Course::query()->find($id);
         if (is_null($course ) || $course->is_deleted==1){
         return $this->sendError("الفيديو غير موجودة","explainvideo is't exists");
         }

        if($course->status === 'active'){
        $course->update(['status' => 'not_active']);
        }
        else{
        $course->update(['status' => 'active']);
        }
        $success['$course']=New CourseResource($course);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة الفيديو بنجاح','explainvideo updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($course)
     {
       $course = Course::query()->find($course);
         if (is_null($course ) || $course->is_deleted==1){
         return $this->sendError("الفيديو غير موجودة","course is't exists");
         }
        $course->update(['is_deleted' => 1]);

        $success['course']=New CourseResource($course);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف الكورس بنجاح','course deleted successfully');
    }
}
