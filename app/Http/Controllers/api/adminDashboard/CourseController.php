<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Unit;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
            'tags'=>'required',
            'videodata.*.video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
              'data.*.title'=>'required|string|max:255',
           'data.*.file'=>'mimes:pdf,doc,excel',
            // 'user_id'=>'required|exists:users,id'
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
             'user_id' => auth()->user()->id,
          ]);

  foreach($request->data as $data)
    {

        $unit= new Unit([
            'title' => $data['title'],
            'file' => $data['file'],
            'course_id' => $course->id,
              ]);

        $unit->save();

        }
        foreach($request->videodata as $videodata)
    {

       $fileName =  $videodata['video']->getClientOriginalName();
        $filePath = 'videos/' . $fileName;

        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($videodata['video']));

        // File URL to access the video in frontend
        $url = Storage::disk('public')->url($filePath);
        $getID3 = new \getID3();
        $pathVideo = 'storage/videos/'. $fileName;

        $fileAnalyze = $getID3->analyze($pathVideo);
        // dd($fileAnalyze);
        $playtime = $fileAnalyze['playtime_string'];
        // dd($playtime);
        if ($isFileUploaded) {
            $video = new Video([
           'duration' => $playtime,
            'video' => $filePath,
            'unit_id' => $unit->id,
        ]);

            $video->save();


        }

    }

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
         $course_id=$course->id;
        if (is_null($course ) || $course->is_deleted==1){
         return $this->sendError("الكورس غير موجودة","course is't exists");
        }
         $input = $request->all();
        $validator =  Validator::make($input ,[
           'name'=>'required|string|max:255',
            'description'=>'required|string',
            'duration' =>'required',
            'tags'=>'required',
            'data.*.title'=>'required|string|max:255',
           'data.*.file'=>'mimes:pdf,doc,excel',
        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
          $cours->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'duration' => $request->input('duration'),
            'tags' => implode(',',$request->input('tags')),
        ]);
      $unit = Unit::where('course_id', $course_id);


    // dd($request->$data['id']);
    $units_id = unit::where('course_id', $course_id)->pluck('id')->toArray();
    foreach ($units_id as $oid) {
      if (!(in_array($oid, array_column($request->data, 'id')))) {
        $unit = Unit::query()->find($oid);
        $unit->update(['is_deleted' => 1]);
      }
    }

    foreach ($request->data as $data) {
      $units[] = Unit::updateOrCreate([
        'id' => $data['id'],
        'course_id' => $course_id,
        'is_deleted' => 0,
      ], [
        'title' => $data['title'],
        'file' => $data['file'],
        'course_id' => $course_id
      ]);
    }

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