<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\HomepageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class HomepageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['Homepages']=HomepageResource::collection(Homepage::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الصفحة الرئبسبة  بنجاح','Homepages return successfully');
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
            'key'=>'required|string',
            'value'=>'required|string'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $Homepage = Homepage::create([
            'key' => $request->key,
            'value' => $request->value,

          ]);


         $success['Homepages']=New HomepageResource($Homepage );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافةالصفحة بنجاح','Homepage Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    public function show($homepage)
    {
        $Homepage= Homepage::query()->find($homepage);
        if ($Homepage->is_deleted==1){
               return $this->sendError("االصفحة غير موجودة","Homepage is't exists");
               }
              $success['homepages']=New HomepageResource($Homepage);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الصفحة بنجاح','Homepage showed successfully');
    }
    public function changeStatus($id)
    {
        $Homepage = Homepage::query()->find($id);
        if ($Homepage->is_deleted==1){
         return $this->sendError("الصفحة غير موجودة","Homepage is't exists");
         }
        if($Homepage->status === 'active'){
            $Homepage->update(['status' => 'not_active']);
     }
    else{
        $Homepage->update(['status' => 'active']);
    }
        $success['homepages']=New HomepageResource($Homepage);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة الصفحة بنجاح',' Homepage status updared successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    public function edit(Homepage $homepage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Homepage $homepage)
    {
        if ($homepage->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة"," homepage is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
               'key'=>'required|string',
            'value'=>'required|string'
           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $homepage->update([
               'key' => $request->input('key'),
               'value' => $request->input('value'),
           ]);

           $success['homepages']=New homepageResource($homepage);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','homepage updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    public function destroy($homepage)
    {
        $homepage =Homepage::query()->find($homepage);
        if ($homepage->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة","Homepage is't exists");
            }
           $homepage->update(['is_deleted' => 1]);

           $success['homepages']=New HomepageResource($homepage);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الصفحة بنجاح','Homepage deleted successfully');
    }
}
