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
      

            $explainvideos = ExplainVideoResource::collection(ExplainVideos::where('is_deleted', 0)->orderByDesc('created_at')->paginate(8));
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

}
