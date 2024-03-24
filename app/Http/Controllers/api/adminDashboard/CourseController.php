<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UnitResource;
use App\Http\Resources\VideoResource;
use App\Models\Course;
use App\Models\Unit;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        $success['courses'] = CourseResource::collection(Course::with(['user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->orderByDesc('created_at')->get());
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
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'data.*.video.*' => 'nullable|string',
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
        if (isset($request->data)) {
            foreach ($request->data as $data) {
                $file = array();
                if (isset($data['file'])) {
                    foreach ($data['file'] as $filedata) {
                        if (is_file($filedata)) {
                            if ($filedata->getClientOriginalName() != null) {
                                $fileName = Str::random(10) . time() . '.' . $filedata->getClientOriginalExtension();
                                $file[] = $fileName;
                                $filePath = 'files/unitfile/' . str_replace(array('\'', '"', "", ' '), '', $fileName);

                                $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($filedata));
                            }

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
                    if (!is_null($data['video']) && $data['video'] != "") {
                        foreach ($data['video'] as $videodata) {

                            // $fileName = Str::random(10) . time() . '.' . $videodata->getClientOriginalExtension();
                            // $filePath = 'videos/' . $fileName;

                            // $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($videodata));

                            // // File URL to access the video in frontend
                            // $url = Storage::disk('public')->url($filePath);
                            // $getID3 = new \getID3();
                            // $pathVideo = 'storage/videos/' . $fileName;

                            // $fileAnalyze = $getID3->analyze($pathVideo);
                            // // dd($fileAnalyze);
                            // $playtimes = $fileAnalyze['playtime_seconds'];
                            // $playtime = gmdate("H:i:s", $playtimes);

                            // if ($isFileUploaded) {

                            // fetch video id from vedio url
                            preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $videodata, $matches);

                            if (isset($matches[1])) {
                                $videoId = $matches[1];

                                $video = new Video([
                                    'video' => $videodata,
                                    'unit_id' => $unit->id,
                                ]);

                                $videodata = $video->get_youtube_title($videoId);
                                $video->name = $videodata[0]['title'];
                                $video->duration = $videodata[0]['duration'];
                                $video->save();
                            } else {
                                return $this->sendError("قم بنسخ الامبداد الخاص بالفيديو من اليوتيوب'", "copy embeded video from youtube");

                            }
                        }
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
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'data.*.video.*' => 'nullable|string',
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

        if (isset($request->data)) {
            if (!is_null($request->data)) {
                foreach ($request->data as $data) {
                    $file = array();
                    if (isset($data['file'])) {
                        foreach ($data['file'] as $filedata) {
                            if (is_file($filedata)) {
                                if ($filedata->getClientOriginalName() != null) {
                                    $fileName = Str::random(10) . time() . '.' . $filedata->getClientOriginalExtension();

                                    $file[] = $fileName;

                                    $filePath = 'files/unitfile/' . str_replace(array('\'', '"', "", ' '), '', $fileName);

                                    $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($filedata));
                                }
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
                        if (!is_null($data['video']) && $data['video'] != "") {
                            foreach ($data['video'] as $videodata) {

                                preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $videodata, $matches);

                                if (isset($matches[1])) {
                                    $videoId = $matches[1];

                                    $video = new Video([
                                        'video' => $videodata,
                                        'unit_id' => $unit->id,
                                    ]);
                                    $videodata = $video->get_youtube_title($videoId);
                                    $video->name = $videodata[0]['title'];
                                    $video->duration = $videodata[0]['duration'];
                                    $video->save();
                                }
                            }
                        }
                    }
                }
            }
        }

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
    public function addvideo(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'video' => 'required|string',
            'unit_id' => 'required|exists:units,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        
        preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $request->video, $matches);

        if (isset($matches[1])) {
            $videoId = $matches[1];
        }
        $video = new Video([
            'video' => $request->video,
            'unit_id' => $request->unit_id,
        ]);
        $videodata = $video->get_youtube_title($videoId);
        $video->name = $videodata[0]['title'];
        $video->duration = $videodata[0]['duration'];
        $video->save();

 
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
