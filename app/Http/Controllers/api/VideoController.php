<?php

namespace App\Http\Controllers\api;
use getID3;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Resources\VideoResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class VideoController extends BaseController
{
   public function showVideo(Request $request)
    {
           $input = $request->all();
        $validator =  Validator::make($input ,[
            'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
         $fileName =   $request->video->getClientOriginalName();
        $filePath = 'videos/' . $fileName;

        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents( $request->video));

        // File URL to access the video in frontend
        $url = Storage::disk('public')->url($filePath);
        $getID3 = new getID3();
        $pathVideo = 'storage/videos/'. $fileName;

        $fileAnalyze = $getID3->analyze($pathVideo);
        // dd($fileAnalyze);
        $playtime = $fileAnalyze['playtime_string'];
        return  $playtime;
    }
}
