<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MaintenanceResource;
use App\Http\Controllers\api\BaseController as BaseController;

class MaintenanceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['Maintenances']=MaintenanceResource::collection(Maintenance::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع و ضع الصيانة بنجاح','Maintenances return successfully');
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
            'message'=>'required',
           // 'store_id'=>'required|exists:stores,id'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $maintenance = Maintenance::create([
            'title' => $request->title,
            'message' => $request->message,
            'store_id'=>$request->store_id
          ]);


         $success['maintenances']=New MaintenanceResource($maintenance);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة و ضع الصيانة بنجاح',' maintenance Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function show( $maintenance)
    {
        $maintenance= Maintenance::query()->find($maintenance);
        if (is_null($maintenance) || $maintenance->is_deleted==1){
               return $this->sendError("وضع الصيانة غير موجودة","Maintenance is't exists");
               }
              $success['maintenances']=New MaintenanceResource($maintenance);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض وضع الصيانة بنجاح','Maintenance showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(Maintenance $maintenance)
    {
        //
    }
    public function changeStatus($id)
    {
        $maintenance= Maintenance::query()->find($id);
        if (is_null($maintenance) || $maintenance->is_deleted==1){
         return $this->sendError("الصيانة غير موجودة","maintenance is't exists");
         }
        if($maintenance->status === 'active'){
            $maintenance->update(['status' => 'not_active']);
     }
    else{
        $maintenance->update(['status' => 'active']);
    }
        $success['maintenances']=New MaintenanceResource($maintenance);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة الصيانة بنجاح',' maintenance status updared successfully');

    }





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        if (is_null($maintenance) || $maintenance->is_deleted==1){
            return $this->sendError("الصيانة غير موجودة"," Maintenance is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                'title'=>'required|string|max:255',
                'message'=>'required',
               // 'store_id'=>'required|exists:stores,id'

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $maintenance->update([
               'title' => $request->input('title'),
               'message' => $request->input('message'),
                'store_id' => $request->input('store_id'),
           ]);

           $success['maintenances']=New MaintenanceResource($maintenance);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','Maintenance updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function destroy($maintenance)
    {
        $maintenance =Maintenance::query()->find($maintenance);
        if (is_null($maintenance) || $maintenance->is_deleted==1){
            return $this->sendError("وضع الصيانة غير موجودة","maintenance is't exists");
            }
           $maintenance->update(['is_deleted' => 1]);

           $success['maintenances']=New MaintenanceResource($maintenance);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف و ضع الصيانة بنجاح','Maintenance deleted successfully');
    }
}