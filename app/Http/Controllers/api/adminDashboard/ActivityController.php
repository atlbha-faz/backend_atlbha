<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\ActivityResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ActivityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['activities']=ActivityResource::collection(Activity::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع الانشطة بنجاح','Activities return successfully');
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
            'name'=>'required|string|max:255'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $activity = Activity::create([
            'name' => $request->name,
          ]);


         $success['activities']=New ActivityResource($activity );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة النشاط بنجاح','Activity Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show($activity)
    {
        $activity= Activity::query()->find($activity);
        if ($activity->is_deleted==1){
               return $this->sendError("النشاط غير موجودة","Activity is't exists");
               }
              $success['activities']=New ActivityResource($activity);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض النشاط بنجاح','Activity showed successfully');
    }
    public function changeStatus($id)
    {
        $activity = Activity::query()->find($id);
        if ($activity->is_deleted==1){
         return $this->sendError("النشاط غير موجودة","Activity is't exists");
         }
        if($activity->status === 'active'){
            $activity->update(['status' => 'not_active']);
     }
    else{
        $activity->update(['status' => 'active']);
    }
        $success['activities']=New ActivityResource($activity);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة النشاط بنجاح',' Activity status updared successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        if ($activity->is_deleted==1){
            return $this->sendError("النشاط غير موجودة"," Activity is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255'

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $activity->update([
               'name' => $request->input('name'),

           ]);

           $success['activities']=New ActivityResource($activity);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','Activity updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy($activity)
    {
        $activity =Activity::query()->find($activity);
        if ($activity->is_deleted==1){
            return $this->sendError("النشاط غير موجودة","activity is't exists");
            }
           $activity->update(['is_deleted' => 1]);

           $success['activities']=New ActivityResource($activity);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف النشاط بنجاح','Activity deleted successfully');
    }
}