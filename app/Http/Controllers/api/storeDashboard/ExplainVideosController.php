<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ExplainVideoResource;
use App\Models\ExplainVideos;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {

        $count= ($request->has('number') && $request->input('number') !== null)? $request->input('number'):10;
        $explainvideos = ExplainVideoResource::collection(ExplainVideos::where('is_deleted', 0)->orderByDesc('created_at')->paginate($count));
        $success['page_count'] = $explainvideos->lastPage();
        $success['current_page'] = $explainvideos->currentPage();
        $success['explainvideos'] = $explainvideos;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الفيديوهات المشروحة بنجاح', 'ExplainVideos return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

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

        $success['explainvideos'] = new ExplainVideoResource($explainVideos);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'explainvideo showed successfully');
    }
    public function explainVideoName(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $explain_videos = ExplainVideos::where('is_deleted', 0)
            ->where('title', 'like', "%$query%")->orderBy('created_at', 'desc')
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $explain_videos->total();
        $success['page_count'] = $explain_videos->lastPage();
        $success['current_page'] = $explain_videos->currentPage();
        $success['explainvideos'] = ExplainVideoResource::collection($explain_videos);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الشروحات بنجاح', 'explain_videos Information returned successfully');

    }

}
