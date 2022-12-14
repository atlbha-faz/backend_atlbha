<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PlanResource;
use App\Http\Controllers\api\BaseController as BaseController;

class PlanController extends BaseController
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
       $success['plans']=PlanResource::collection(Plan::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الخطط بنجاح','plans return successfully');
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
            'name'=>'required|string|max:255',


        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $plan = Plan::create([
            'name' => $request->name,

          ]);


         $success['plans']=New PlanResource($plan);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة خطة بنجاح','plan Added successfully');



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plane  $plan
     * @return \Illuminate\Http\Response
     */
    public function show($plan)
    {

          $plan = Plan::query()->find($plan);
         if (is_null($plan) || $plan->is_deleted == 1){
         return $this->sendError(" الخطة غير موجودة","plan is't exists");
         }


        $success['plans']=New PlanResource($plan);
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض الخطة بنجاح','plan showed successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plane
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
       {
         if (is_null($plan) || $plan->is_deleted==1){
         return $this->sendError(" الخطة غير موجود","plan is't exists");
          }
         $input = $request->all();
         $validator =  Validator::make($input ,[
           'name'=>'required|string|max:255',

         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         }
         $plan->update([
            'name' => $request->input('name'),


         ]);
         //$country->fill($request->post())->update();
            $success['plans']=New PlanResource($plan);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','plan updated successfully');
        }

       public function changeStatus($id)
    {
        $plan = Plan::query()->find($id);
         if (is_null($plan) || $plan->is_deleted==1){
         return $this->sendError(" الباقة غير موجود","plan is't exists");
         }

        if($plan->status === 'active'){
        $plan->update(['status' => 'not_active']);
        }
        else{
        $plan->update(['status' => 'active']);
        }
        $success['plans']=New PlanResource($plan);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة الباقة بنجاح','plan updated successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy($plan)
    {
       $plan = Plan::query()->find($plan);
         if (is_null($plan) || $plan->is_deleted==1){
         return $this->sendError("الباقة غير موجودة","plan is't exists");
         }
        $plan->update(['is_deleted' => 1]);

        $success['plans']=New PlanResource($plan);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف الخطة بنجاح','plan deleted successfully');
    }
}