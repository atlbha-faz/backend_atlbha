<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\ActivityResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ActivityController extends BaseController
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
            'name'=>'required|string|max:255|unique:activities,name',
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
        if ( is_null($activity) || $activity->is_deleted==1){
            return $this->sendError("النشاط غير موجودة"," Activity is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255|unique:activities,name,'.$activity->id,

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
 public function deleteall(Request $request)
    {


            $activities =Activity::whereIn('id',$request->id)->get();
           foreach($activities as $activity)
           {
             if (is_null($activity) || $activity->is_deleted==1){
                   return $this->sendError("النشاط غير موجودة"," Activity is't exists");
       }
               $activity->update(['is_deleted' => 1]);
        $success['activities']= New ActivityResource($activity);

            }
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }
}
