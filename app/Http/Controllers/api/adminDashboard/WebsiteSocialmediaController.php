<?php

namespace App\Http\Controllers\api\adminDashboard;
use Illuminate\Http\Request;
use App\Models\website_socialmedia;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\website_socialmediaResource;
use App\Http\Controllers\api\BaseController as BaseController;

class WebsiteSocialmediaController extends BaseController
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
        $success['website_socialmedia']=website_socialmediaResource::collection(website_socialmedia::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع وسائل التواصل بنجاح',' website_socialmedia return successfully');
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
            'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'link' =>'required|url',

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $website_socialmedia = website_socialmedia::create([
            'name' => $request->name,
            'logo'=>$request->logo,
            'link' =>$request->link

          ]);

         $success['website_socialmedia']=New website_socialmediaResource($website_socialmedia);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة وسائل التواصل بنجاح',' website_socialmedia Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\website_socialmedia  $website_socialmedia
     * @return \Illuminate\Http\Response
     */
    public function show($website_socialmedia)
    {
        $website_socialmedia = website_socialmedia::query()->find($website_socialmedia);
             if (is_null($website_socialmedia) || $website_socialmedia->is_deleted==1){
             return $this->sendError("وسائل التواصل غير موجودة"," website_socialmedia is't exists");
             }


            $success['website_socialmedia']=New website_socialmediaResource($website_socialmedia);
            $success['status']= 200;

             return $this->sendResponse($success,'تم  عرض بنجاح',' website_socialmedia showed successfully');

        }
        public function changeStatus($id)
        {
            $website_socialmedia =website_socialmedia::query()->find($id);
             if (is_null($website_socialmedia) || $website_socialmedia->is_deleted==1){
             return $this->sendError("وسائل التواصل غير موجودة","website_socialmedia is't exists");
             }

            if($website_socialmedia->status === 'active'){
            $website_socialmedia->update(['status' => 'not_active']);
            }
            else{
            $website_socialmedia->update(['status' => 'active']);
            }
            $success['website_socialmedia']=New website_socialmediaResource($website_socialmedia);
            $success['status']= 200;

             return $this->sendResponse($success,'تم تعديل حالة  بنجاح','website_socialmedia updated successfully');

        }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\website_socialmedia  $website_socialmedia
     * @return \Illuminate\Http\Response
     */
    public function edit(website_socialmedia $website_socialmedia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\website_socialmedia  $website_socialmedia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $website_socialmedia)
    {
        $website_socialmedia =website_socialmedia::query()->find($website_socialmedia);
        if (is_null($website_socialmedia) || $website_socialmedia->is_deleted==1){
            return $this->sendError("وسائل التواصل غير موجودة","website_socialmedia is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255',
                'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
               'link' =>'required|url',

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $website_socialmedia->update([
               'name' => $request->input('name'),
               'logo' => $request->input('logo'),
               'link' => $request->input('link'),

           ]);

           $success['website_socialmedia']=New website_socialmediaResource($website_socialmedia);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','website_socialmedia updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\website_socialmedia  $website_socialmedia
     * @return \Illuminate\Http\Response
     */
    public function destroy($website_socialmedia)
    {
     $website_socialmedia =website_socialmedia::query()->find($website_socialmedia);

        if (is_null($website_socialmedia) || $website_socialmedia->is_deleted==1){
            return $this->sendError(" وسائل التواصل غير موجودة","website_socialmedia is't exists");
            }
           $website_socialmedia->update(['is_deleted' => 1]);

           $success['website_socialmedia']=New website_socialmediaResource($website_socialmedia);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف وسائل التواصل بنجاح','website_socialmedia deleted successfully');
    }
}