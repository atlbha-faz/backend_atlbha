<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\HomepageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class HomepageController extends BaseController
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
        $success['Homepages']=New HomepageResource(Homepage::where('store_id',null)->first());
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */


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



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */

     public function logoUpdate(Request $request)
    {
        $logohomepage =Homepage::where('store_id',null)->first();

        if (is_null($logohomepage) || $logohomepage->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة"," homepage is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
            'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
              ]);
           if ($validator->fails())
           {
               return $this->sendError(null,$validator->errors());
           }
         $logohomepage->updateOrCreate([
            'store_id'   => null,
               ],[
               'logo' => $request->logo,
                  ]);

           $success['homepages']=New HomepageResource($logohomepage);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','homepage updated successfully');
}

public function panarUpdate(Request $request)
{
    $panarhomepage =Homepage::where('store_id',null)->first();
    if (is_null($panarhomepage) || $panarhomepage->is_deleted==1){
        return $this->sendError("الصفحة غير موجودة"," homepage is't exists");
   }
        $input = $request->all();
       $validator =  Validator::make($input ,[
        'panar1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        'panarstatus1'=>'required|in:active,not_active',
        'panar2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        'panarstatus2'=>'required|in:active,not_active',
        'panar3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        'panarstatus3'=>'required|in:active,not_active',
          ]);
       if ($validator->fails())
       {
           # code...
           return $this->sendError(null,$validator->errors());
       }


     $panarhomepage->updateOrCreate([
        'store_id'   => null,
    ],[
                'panar1' => $request->panar1,
                'panarstatus1' => $request->panarstatus1,
                'panar2' => $request->panar2,
                'panarstatus2' => $request->panarstatus2,
                'panar3' => $request->panar3,
                'panarstatus3' => $request->panarstatus3,
              ]);

       $success['panarhomepages']=New HomepageResource($panarhomepage);
       $success['status']= 200;

        return $this->sendResponse($success,'تم التعديل بنجاح','homepage updated successfully');
}

public function sliderUpdate(Request $request)
{
    $sliderhomepage = Homepage::where('store_id',null)->first();
    if (is_null($sliderhomepage) || $sliderhomepage->is_deleted==1){
        return $this->sendError("الصفحة غير موجودة"," homepage is't exists");
   }
        $input = $request->all();
       $validator =  Validator::make($input ,[
        'slider1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        'sliderstatus1'=>'required|in:active,not_active',
        'slider2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        'sliderstatus2'=>'required|in:active,not_active',
        'slider3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        'sliderstatus3'=>'required|in:active,not_active',
          ]);
       if ($validator->fails())
       {
           # code...
           return $this->sendError(null,$validator->errors());
       }


     $sliderhomepage->updateOrCreate([
            'store_id'   => null,
            ],[
                'slider1' => $request->slider1,
                'sliderstatus1' => $request->sliderstatus1,
                'slider2' => $request->slider2,
                'sliderstatus2' => $request->sliderstatus2,
                'slider3' => $request->slider3,
                'sliderstatus3' => $request->sliderstatus3,
              ]);

       $success['sliderhomepages']=New HomepageResource($sliderhomepage);
       $success['status']= 200;

        return $this->sendResponse($success,'تم التعديل بنجاح','homepage updated successfully');
}

}
