<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\VideoResource;
use Illuminate\Support\Facades\Storage;
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
       $success['videos']=VideoResource::collection(Video::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الفيديوهات بنجاح','videos return successfully');
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
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show($video)
    {
          $video = Video::query()->find($video);
         if (is_null($video ) || $video->is_deleted == 1){
         return $this->sendError("الوالفيديو غير موجود","video is't exists");
         }


        $success['videos']=New VideoResource($video);
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض بنجاح','video showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */

}
