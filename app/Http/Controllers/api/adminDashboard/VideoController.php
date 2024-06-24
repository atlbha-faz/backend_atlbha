<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Requests\VideoRequest;
use App\Http\Resources\VideoResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class VideoController extends BaseController
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
        $success['videos'] = VideoResource::collection(Video::where('is_deleted', 0)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الفيديوهات بنجاح', 'videos return successfully');
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
    public function store(VideoRequest $request)
    {
        $input = $request->all();
 
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
            $video = new Video();

            $video->duration = $playtime;
            $video->video = $filePath;
            $video->unit_id = $request->unit_id;
            $video->save();
        }

        $success['videos'] = new VideoResource($video);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة فيديو بنجاح', 'video Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show($video)
    {
        $video = Video::query()->find($video);
        if (is_null($video) || $video->is_deleted != 0) {
            return $this->sendError("الوحدة غير موجودة", "video is't exists");
        }

        $success['videos'] = new VideoResource($video);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بنجاح', 'video showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(VideoRequest $request, $video)
    {
        $video = Video::query()->find($video);
        if (is_null($video) || $video->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "video is't exists");
        }
        $input = $request->all();
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
        if ($isFileUploaded) {

            $video->update([
                'video' => $request->input('video'),
                'unit_id' => $request->input('unit_id'),

            ]);
        }
        $success['videos'] = new VideoResource($video);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'video updated successfully');
    }

    public function changeStatus($id)
    {
        $video = Video::query()->find($id);
        if (is_null($video) || $video->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "video is't exists");
        }

        if ($video->status === 'active') {
            $video->update(['status' => 'not_active']);
        } else {
            $video->update(['status' => 'active']);
        }
        $success['videos'] = new VideoResource($video);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة الفيديو بنجاح', 'video updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy($video)
    {
        $video = Video::query()->find($video);
        if (is_null($video) || $video->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "video is't exists");
        }
        $video->update(['is_deleted' => $video->id]);

        $success['video'] = new VideoResource($video);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الفيديو بنجاح', 'video deleted successfully');
    }
}
