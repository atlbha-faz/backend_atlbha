<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Replaycomment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReplaycommentResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ReplaycommentController extends BaseController
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

        $success['replaycomment']=ReplaycommentResource::collection(Replaycomment::where('is_deleted',0)->orderByDesc('created_at')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع ردود التعليقات بنجاح','replay comment return successfully');
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
            'comment_text'=>'required|string|max:255',
            'comment_id'=>'required|exists:comments,id',
            'user_id'=>'required|exists:users,id'


        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $replaycomment = Replaycomment::create([
            'comment_text' => $request->comment_text,
            'comment_id' => $request->comment_id,
            'user_id' => $request->user_id,

          ]);


         $success['replaycomments']=New ReplaycommentResource($replaycomment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة رد تعليق بنجاح','replay comment Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function show($replaycomment)
   {
        $replaycomment = Replaycomment::query()->find($replaycomment);
        if (is_null($replaycomment ) || $replaycomment->is_deleted==1){
        return $this->sendError("'رد التعليق غير موجودة","replay comment type is't exists");
        }


       $success['replaycomments']=New ReplaycommentResource($replaycomment);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض رد التعليق بنجاح','replay comment showed successfully');
     }
     

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function edit(Replaycomment $replaycomment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $replaycomment)
     {
        $replaycomment = Replaycomment::query()->find($replaycomment);
         if (is_null($replaycomment ) || $replaycomment->is_deleted==1){
         return $this->sendError(" التعليق غير موجود","replay comment is't exists");
          }

         $input = $request->all();
         $validator =  Validator::make($input ,[
            'comment_text'=>'required|string|max:255',
            'comment_id'=>'required|exists:comments,id',
            'user_id'=>'required|exists:users,id'
         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         }
         $replaycomment->update([
            'comment_text' => $request->input('comment_text'),
            'comment_id' => $request->input('comment_id'),
           'user_id' => $request->input('user_id'),
         ]);
         //$country->fill($request->post())->update();
            $success['replaycomments']=New ReplaycommentResource($replaycomment);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','replay comment updated successfully');
    }

     public function changeStatus($id)
    {
        $replaycomment = Replaycomment::query()->find($id);
         if (is_null($replaycomment ) || $replaycomment->is_deleted==1){
         return $this->sendError("التعليق غير موجود","replaycomment is't exists");
         }

        if($replaycomment->status === 'active'){
        $replaycomment->update(['status' => 'not_active']);
        }
        else{
        $replaycomment->update(['status' => 'active']);
        }
        $success['replaycomments']=New ReplaycommentResource($replaycomment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة رد التعليق بنجاح','replay comment updated successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function destroy($replaycomment)
    {
       $replaycomment = Replaycomment::query()->find($replaycomment);
         if (is_null($replaycomment ) || $replaycomment->is_deleted==1){
         return $this->sendError("التعليق غير موجود","replaycomment is't exists");
         }
        $replaycomment->update(['is_deleted' => 1]);

        $success['replaycomments']=New ReplaycommentResource($replaycomment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف  رد التعليق بنجاح',' replay comment deleted successfully');
    }
}