<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Unit;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\UnitResource;
use App\Http\Resources\VideoResource;
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

        $success['courses'] = CourseResource::collection(Course::where('is_deleted', 0)->orderByDesc('created_at')->get());
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
    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'required',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'data.*.video.*' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            'data.*.title' => 'required|string|max:255',
            'data.*.file.*' => 'nullable|mimes:pdf,doc,excel',
            // 'user_id'=>'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'duration' => $request->duration,
            'tags' => $request->tags,
            'image' => $request->image,
            'user_id' => auth()->user()->id,
        ]);

        foreach ($request->data as $data) {
            $file = array();
            if (isset($data['file'])) {
                foreach ($data['file'] as $filedata) {
                    if ($filedata->getClientOriginalName() != null) {
                        $fileName = Str::random(10) . time() . '.' . $filedata->getClientOriginalExtension();
                        $file[] = $fileName;
                        $filePath = 'files/unitfile/' . str_replace(array('\'', '"', "", ' '), '', $fileName);

                        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($filedata));
                    }

                }
            }
            $unit = new Unit([
                'title' => $data['title'],
                'file' => count($file) > 0 ? implode(',', $file) : null,
                'course_id' => $course->id,
            ]);

            $unit->save();
            if (isset($data['video'])) {
                foreach ($data['video'] as $videodata) {

                    $fileName = Str::random(10) . time() . '.' . $videodata->getClientOriginalExtension();
                    $filePath = 'videos/' . $fileName;

                    $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($videodata));

                    // File URL to access the video in frontend
                    $url = Storage::disk('public')->url($filePath);
                    $getID3 = new \getID3();
                    $pathVideo = 'storage/videos/' . $fileName;

                    $fileAnalyze = $getID3->analyze($pathVideo);
                    // dd($fileAnalyze);
                    $playtimes = $fileAnalyze['playtime_seconds'];
                    $playtime = gmdate("H:i:s", $playtimes);

                    if ($isFileUploaded) {
                        $video = new Video([
                            'duration' => $playtime,
                            'video' => $fileName,
                            'name' => $fileName,
                            'unit_id' => $unit->id,
                        ]);

                        $video->save();

                    }

                }
            }
        }

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

        $success['$courses'] = new CourseResource($course);
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
    public function update(Request $request, $course)
    {
        $course = Course::where('id', $course)->first();

        if (is_null($course) || $course->is_deleted != 0) {
            return $this->sendError("الكورس غير موجودة", "course is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'required',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'data.*.video.*' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            'data.*.title' => 'required|string|max:255',
            'data.*.file.*' => 'nullable|mimes:pdf,doc,excel',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $course_id = $course->id;
        $course->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $request->image,
            'duration' => $request->input('duration'),
            'tags' => $request->input('tags'),
        ]);
        $unit = Unit::where('course_id', $course_id);

        // dd($request->$data['id']);
        /*   $units_id = Unit::where('course_id', $course_id)->pluck('id')->toArray();
        foreach ($units_id as $oid) {
        if (!(in_array($oid, array_column($request->data, 'id')))) {
        $unit = Unit::query()->find($oid);
        $unit->update(['is_deleted' => 1]);
        }
        }

        foreach ($request->data as $data) {
        $file=array();
        foreach($data['file'] as $filedata)
        {
        // $file[]=$filedata;
        $file[]=$filedata->getClientOriginalName();
        }

        $units[] = Unit::updateOrCreate([
        'id' => $data['id'],
        'course_id' => $course_id,
        'is_deleted' => 0,
        ], [
        'title' => $data['title'],
        'file' => implode(',',$file),

        ]);
        $videos_id = Video::where('unit_id', $data['id'])->pluck('id')->toArray();
        foreach ($videos_id as $oid) {
        $video = Video::query()->find($oid);
        $video->update(['is_deleted' => 1]);
        }

        $units = Unit::where('course_id', $course_id)->get;

        foreach ($units  as $unit) {

        $videos_id = Video::where('unit_id', $unit->id)->pluck('id')->toArray();
        foreach ($videos_id as $oid) {
        $video = Video::query()->find($oid);
        $video->update(['is_deleted' => 1]);

        }
        foreach($data['video'] as $videodata)
        {

        $fileName =  $videodata->getClientOriginalName();
        $filePath = 'videos/' . $fileName;

        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($videodata));

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
        'video' => $fileName,
        'name'=> $fileName ,
        'unit_id' => $data['id'],
        ]);

        $video->save();

        }

        }

        }

        }*/
        if (!is_null($request->data)) {
            foreach ($request->data as $data) {
                $file = array();
                if (isset($data['file'])) {
                    foreach ($data['file'] as $filedata) {
                        if ($filedata->getClientOriginalName() != null) {
                            $fileName = Str::random(10) . time() . '.' . $filedata->getClientOriginalExtension();

                            $file[] = $fileName;

                            $filePath = 'files/unitfile/' . str_replace(array('\'', '"', "", ' '), '', $fileName);

                            $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($filedata));
                        }
                    }
                }

                $unit = new Unit([
                    'title' => $data['title'],
                    'file' => count($file) > 0 ? implode(',', $file) : null,
                    'course_id' => $course->id,
                ]);

                $unit->save();
                if (isset($data['video'])) {
                    foreach ($data['video'] as $videodata) {

                        $fileName = Str::random(10) . time() . '.' . $videodata->getClientOriginalExtension();
                        $filePath = 'videos/' . $fileName;

                        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($videodata));

                        // File URL to access the video in frontend
                        $url = Storage::disk('public')->url($filePath);
                        $getID3 = new \getID3();
                        $pathVideo = 'storage/videos/' . $fileName;

                        $fileAnalyze = $getID3->analyze($pathVideo);
                        // dd($fileAnalyze);
                        $playtimes = $fileAnalyze['playtime_seconds'];
                        $playtime = gmdate("H:i:s", $playtimes);

                        // dd($playtime);
                        if ($isFileUploaded) {
                            $video = new Video([
                                'duration' => $playtime,
                                'video' => $fileName,
                                'name' => $fileName,
                                'unit_id' => $unit->id,
                            ]);

                            $video->save();

                        }

                    }
                }
            }

        }

        //$country->fill($request->post())->update();
        $success['courses'] = new CourseResource($course);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'course updated successfully');
    }

    /* public function changeStatus($id)
    {
    $course = Course::query()->find($id);
    if (is_null($course ) || $course->is_deleted !=0){
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

    }*/

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
    public function addvideo(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            'unit_id' => 'required|exists:units,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $fileName = $request->video->getClientOriginalName();
        $filePath = 'videos/' . $fileName;

        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));

        // File URL to access the video in frontend
        $url = Storage::disk('public')->url($filePath);
        $getID3 = new \getID3();
        $pathVideo = 'storage/videos/' . $fileName;

        $fileAnalyze = $getID3->analyze($pathVideo);
        // dd($fileAnalyze);
        $playtime = $fileAnalyze['playtime_string'];
        // dd($playtime);
        if ($isFileUploaded) {
            $video = new Video([
                'duration' => $playtime,
                'video' => $filePath,
                'name' => $fileName,
                'unit_id' => $request->unit_id,
            ]);

            $video->save();
        }
        $success['videos'] = new VideoResource($video);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة فيديو بنجاح', 'video Added successfully');

    }

    public function deletevideo($video_id)
    {
        $video = Video::query()->find($video_id);
        if (is_null($video) || $video->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "video is't exists");
        }

        $video->update(['is_deleted' => $video->id]);
        $success['videos'] = new VideoResource($video);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف بنجاح', 'video deleted successfully');
    }
    public function deleteunit($unit)
    {
        $unit = Unit::query()->find($unit);
        if (is_null($unit) || $unit->is_deleted != 0) {
            return $this->sendError("الوحدة غير موجودة", "unit is't exists");
        }
        $unit->update(['is_deleted' => $unit->id]);

        $success['unit'] = new UnitResource($unit);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الوحدة بنجاح', 'unit deleted successfully');
    }
}
