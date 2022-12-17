<?php

namespace App\Http\Controllers\api\adminDashboard;

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
        $validator =  Validator::make($input ,[
            'title'=>'required|string|max:255',
            'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            'thumbnail' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             'user_id'=>'required|exists:users,id'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $explainvideos = ExplainVideos::create([
            'title' => $request->title,
            'video'=>$request->video,
            'thumbnail' =>$request->thumbnail,
            'user_id' => $request->user_id,
          ]);

         // return new CountryResource($country);
         $success['explainvideos']=New ExplainVideoResource($explainvideos);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة فيديو بنجاح','video Added successfully');
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
         if (is_null($explainVideos) || $explainVideos->is_deleted == 1){
         return $this->sendError("المدينه غير موجودة","explainvideo is't exists");
         }


        $success['$explainvideos']=New ExplainVideoResource($explainVideos);
        $success['status']= 200;

         return $this->sendResponse($success,'تم  عرض بنجاح','explainvideo showed successfully');
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
    public function update(Request $request, ExplainVideos $explainVideos)
 {
        if (is_null($explainVideos) || $explainVideos->is_deleted==1){
         return $this->sendError("الفيديو غير موجودة","explainvideo is't exists");
    }
         $input = $request->all();
        $validator =  Validator::make($input ,[
             'title'=>'required|string|max:255',
            'video'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            'thumbnail' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             'user_id'=>'required|exists:users,id'
        ]);
        if ($validator->fails())
        {
            # code...
            return $this->sendError(null,$validator->errors());
        }
        $explainVideos->update([
            'title' => $request->input('title'),
            'video' => $request->input('video'),
            'thumbnail' => $request->input('thumbnail'),
            'user_id' => $request->input('user_id')
        ]);
       //$country->fill($request->post())->update();
        $success['explainvideos']=New ExplainVideoResource($explainVideos);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','explainvideo updated successfully');
}


      public function changeStatus($id)
    {
        $explainvideos = ExplainVideos::query()->find($id);
         if (is_null($$explainvideos) || $explainvideos->is_deleted==1){
         return $this->sendError("الفيديو غير موجودة","explainvideo is't exists");
         }

        if($explainvideos->status === 'active'){
        $explainvideos->update(['status' => 'not_active']);
        }
        else{
        $explainvideos->update(['status' => 'active']);
        }
        $success['$explainvideos']=New ExplainVideoResource($explainvideos);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة الفيديو بنجاح','explainvideo updated successfully');

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
         if (is_null($explainVideos) || $explainVideos->is_deleted==1){
         return $this->sendError("الفيديو غير موجودة","explainVideos is't exists");
         }
        $explainVideos->update(['is_deleted' => 1]);

        $success['explainVideos']=New ExplainVideoResource($explainVideos);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف الفيديو بنجاح','explainVideos deleted successfully');
    }
}