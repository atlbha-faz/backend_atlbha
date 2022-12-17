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
            'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'panar1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'panarstatus1'=>'required|in:active,not_active',
            'panar2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'panarstatus2'=>'required|in:active,not_active',
            'panar3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'panarstatus3'=>'required|in:active,not_active',
            'clientstatus'=>'required|in:active,not_active',
            'commentstatus'=>'required|in:active,not_active',
            'slider1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'sliderstatus1'=>'required|in:active,not_active',
            'slider2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'sliderstatus2'=>'required|in:active,not_active',
            'slider3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'sliderstatus3'=>'required|in:active,not_active',
        
        ]);

        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }

        $Homepage = Homepage::updateOrCreate([
            'store_id'   => null,
            ],[
           'logo' => $request->logo,
           'panar1' => $request->panar1,
           'panarstatus1' => $request->panarstatus1,
           'panar2' => $request->panar2,
           'panarstatus2' => $request->panarstatus2,
           'panar3' => $request->panar3,
           'panarstatus3' => $request->panarstatus3,
           'clientstatus' => $request->clientstatus,
           'commentstatus' => $request->commentstatus,
           'slider1' => $request->slider1,
           'sliderstatus1' => $request->sliderstatus1,
           'slider2' => $request->slider2,
           'sliderstatus2' => $request->sliderstatus2,
           'slider3' => $request->slider3,
           'sliderstatus3' => $request->sliderstatus3,
    
       ]);

         $success['Homepages']=New HomepageResource($Homepage);
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
        if (is_null($Homepage) || $Homepage->is_deleted==1){
               return $this->sendError("االصفحة غير موجودة","Homepage is't exists");
               }
              $success['homepages']=New HomepageResource($Homepage);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الصفحة بنجاح','Homepage showed successfully');
    }
    public function changeStatus($id)
    {
        $Homepage = Homepage::query()->find($id);
        if (is_null($Homepage) || $Homepage->is_deleted==1){
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

    public function update(Request $request,Homepage $homepage)
    {
        
       
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
        if (is_null($homepage) || $homepage->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة","Homepage is't exists");
            }
           $homepage->update(['is_deleted' => 1]);

           $success['homepages']=New HomepageResource($homepage);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الصفحة بنجاح','Homepage deleted successfully');
    }
}