<?php

namespace App\Http\Controllers\api\adminDashboard;

use Illuminate\Http\Request;
use App\Models\Notification_type;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Notification_typeResource;
use App\Http\Controllers\api\BaseController as BaseController;

class Notification_typesController extends BaseController
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
        $success['Notification_types']=Notification_typeResource::collection(Notification_type::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع انواع الاشعارات بنجاح','Notification_type return successfully');
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
        $notification_type = Notification_type::create([
            'name' => $request->name,
          ]);


         $success['notification_types']=New Notification_typeResource($notification_type);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة نوع الاشعار بنجاح','notification_type Added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification_type= Notification_type::query()->find($id);
        if (is_null($notification_type) || $notification_type->is_deleted==1){
               return $this->sendError("نوع الاشعار غير موجودة","Notification_type is't exists");
               }
              $success['notification_types']=New Notification_typeResource($notification_type);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض نوع الاشعار بنجاح','Notification_type showed successfully');
    }
    public function changeStatus($id)
    {
        $notification_type = Notification_type::query()->find($id);
        if (is_null($notification_type) || $notification_type->is_deleted==1){
         return $this->sendError(" نوع الاشعار غير موجودة","Notification_type is't exists");
         }
        if($notification_type->status === 'active'){
            $notification_type->update(['status' => 'not_active']);
     }
    else{
        $notification_type->update(['status' => 'active']);
    }
        $success['notification_types']=New Notification_typeResource($notification_type);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة نوع الاشعار بنجاح',' Notification_type status updared successfully');

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    $notification_type = Notification_type::query()->find($id);

        if (is_null($notification_type) || $notification_type->is_deleted==1){
            return $this->sendError("نوع الاشعار غير موجودة"," notification_type is't exists");
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
           $notification_type->update([
               'name' => $request->input('name'),

           ]);

           $success['notification_types']=New Notification_typeResource($notification_type);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','notification_type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification_type =Notification_type::query()->find($id);
        if (is_null($notification_type) || $notification_type->is_deleted==1){
            return $this->sendError("نوع الاشعار غير موجودة","notification_type is't exists");
            }
           $notification_type->update(['is_deleted' => 1]);

           $success['notification_types']=New Notification_typeResource($notification_type);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف نوع الاشعار بنجاح','notification_type deleted successfully');
    }
}