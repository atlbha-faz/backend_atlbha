<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\ExplainVideos;
use Illuminate\Http\Request;
use App\Http\Resources\ExplainVideoResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;
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
       $success['explainvideos']=ExplainVideoResource::collection(ExplainVideos::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الفيديوهات المشروحة بنجاح','ExplainVideos return successfully');
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
         if (is_null($explainVideos) || $explainVideos->is_deleted == 1){
         return $this->sendError("الشرح غير موجودة","explainvideo is't exists");
         }


        $success['explainvideos']=New ExplainVideoResource($explainVideos);
        $success['status']= 200;

         return $this->sendResponse($success,'تم  عرض بنجاح','explainvideo showed successfully');
    }







}
