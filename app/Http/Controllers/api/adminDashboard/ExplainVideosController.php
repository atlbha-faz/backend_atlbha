<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ExplainVideoResource;
use App\Models\ExplainVideos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExplainVideosController extends BaseController
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
        $success['explainvideos'] = ExplainVideoResource::collection(ExplainVideos::with(['user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الفيديوهات المشروحة بنجاح', 'ExplainVideos return successfully');
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
            'title' => 'required|string|max:255',
            'video' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1048',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        if (isset($request->video)) {
            preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $request->video, $matches);

            if (isset($matches[1])) {
                $videoId = $matches[1];

                $explainvideos = new ExplainVideos([
                    'title' => $request->title,
                    'video' => $request->video,
                    'thumbnail' => $request->thumbnail,
                    'user_id' => auth()->user()->id,

                ]);
                $videodata = $explainvideos->get_youtube_title($videoId);
                $explainvideos->duration = $videodata[0]['duration'];
                $explainvideos->save();
            } else {
                return $this->sendResponse($success, 'قم بنسخ الامبداد الخاص بالفيديو من اليوتيوب', 'copy embeded video from youtube');

            }
        }

        $success['explainvideos'] = new ExplainVideoResource($explainvideos);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة فيديو بنجاح', 'video Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExplainVideos  $explainVideos
     * @return \Illuminate\Http\Response
     */
    public function show($explainVideos)
    {
        $explainVideos = ExplainVideos::query()->find($explainVideos);
        if (is_null($explainVideos) || $explainVideos->is_deleted != 0) {
            return $this->sendError("الشرح غير موجودة", "explainvideo is't exists");
        }

        $success['$explainvideos'] = new ExplainVideoResource($explainVideos);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'explainvideo showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExplainVideos  $explainVideos
     * @return \Illuminate\Http\Response
     */
    public function edit(ExplainVideos $explainVideos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExplainVideos  $explainVideos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $explainVideos)
    {
        $explainVideosoObject = ExplainVideos::query()->find($explainVideos);

        if (is_null($explainVideosoObject) || $explainVideosoObject->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "explainvideo is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
            'video' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1048',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        if (isset($request->video)) {
            preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $request->video, $matches);

            if (isset($matches[1])) {
                $videoId = $matches[1];
            }

            $explainvideos = $explainVideosoObject->update([
                'title' => $request->input('title'),
                'video' => $request->input('video'),

                'thumbnail' => $request->thumbnail,
            ]);

            $videodata = $explainVideosoObject->get_youtube_title($videoId);

            if (!is_null($request->video)) {

                $explainvideos = $explainVideosoObject->update([
                    'duration' => $videodata[0]['duration'],

                ]);
            }
        }

        $success['explainvideos'] = new ExplainVideoResource($explainVideosoObject);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'explainvideo updated successfully');
    }

    public function changeStatus($id)
    {
        $explainvideos = ExplainVideos::query()->find($id);
        if (is_null($$explainvideos) || $explainvideos->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "explainvideo is't exists");
        }

        if ($explainvideos->status === 'active') {
            $explainvideos->update(['status' => 'not_active']);
        } else {
            $explainvideos->update(['status' => 'active']);
        }
        $success['$explainvideos'] = new ExplainVideoResource($explainVideos);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة الفيديو بنجاح', 'explainvideo updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExplainVideos  $explainVideos
     * @return \Illuminate\Http\Response
     */
    public function destroy($explainVideos)
    {
        $explainVideos = ExplainVideos::query()->find($explainVideos);
        if (is_null($explainVideos) || $explainVideos->is_deleted != 0) {
            return $this->sendError("الفيديو غير موجودة", "explainVideos is't exists");
        }
        $explainVideos->update(['is_deleted' => $explainVideos->id]);

        $success['explainVideos'] = new ExplainVideoResource($explainVideos);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الفيديو بنجاح', 'explainVideos deleted successfully');
    }
}
