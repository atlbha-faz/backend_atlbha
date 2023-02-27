<?php

namespace App\Http\Controllers\api;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Resources\VideoResource;
use App\Http\Controllers\api\BaseController as BaseController;

class VideoController extends BaseController
{
   public function showVideo($video)
    {
          $video = Video::query()->find($video);
         if (is_null($video ) || $video->is_deleted == 1){
         return $this->sendError("الفيديو غير موجودة","video is't exists");
         }

     $success['video']=New VideoResource($video);
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض بنجاح','video showed successfully');

    }
}
