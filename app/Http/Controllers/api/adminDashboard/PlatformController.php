<?php

namespace App\Http\Controllers\api\adminDashboard;


use App\Models\Platform;
use Illuminate\Http\Request;
use App\Http\Resources\PlatformResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class PlatformController extends BaseController
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
        $success['platforms']=PlatformResource::collection(Platform::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع السوق  بنجاح','Platforms return successfully');
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
        $platform = Platform::create([
            'name' => $request->name,
            'logo'=>$request->logo,
            'link' =>$request->link,
          ]);


         $success['platforms']=New PlatformResource($platform);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة السوق  بنجاح','Platform Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GeneralShop  $generalShop
     * @return \Illuminate\Http\Response
     */
    public function show($platform)
    {
        $platform = Platform::query()->find($platform);
        if (is_null($platform ) || $platform->is_deleted==1){
        return $this->sendError("السوق  غير موجودة","platform is't exists");
        }


       $success['platforms']=New PlatformResource($platform);
       $success['status']= 200;

        return $this->sendResponse($success,'تم  عرض بنجاح','platform showed successfully');
    }

    public function changeStatus($id)
    {
        $platform = Platform::query()->find($id);
         if (is_null($platform ) || $platform->is_deleted==1){
         return $this->sendError("السوق غير موجودة","platform is't exists");
         }

        if($platform->status === 'active'){
        $platform->update(['status' => 'not_active']);
        }
        else{
        $platform->update(['status' => 'active']);
        }
        $success['platforms']=New PlatformResource($platform);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة السوق بنجاح','platform updated successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GeneralShop  $generalShop
     * @return \Illuminate\Http\Response
     */
    public function edit(Platform $platform)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GeneralShop  $generalShop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Platform $platform)
    {
        if (is_null($platform ) || $platform->is_deleted==1){
            return $this->sendError("االسوق غير موجودة","platform is't exists");
                }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255',
               'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
               'link' =>'required|url'
           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $platform->update([
               'name' => $request->input('name'),
               'logo' => $request->input('logo'),
               'link' => $request->input('link'),
           ]);

           $success['platforms']=New PlatformResource($platform);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','platform updated successfully');
       }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GeneralShop  $generalShop
     * @return \Illuminate\Http\Response
     */
    public function destroy( $platform)
    {
        $platform = Platform::query()->find($platform);
        if (is_null($platform ) || $platform->is_deleted==1){
            return $this->sendError("السوق غير موجودة","platform is't exists");
            }
           $platform->update(['is_deleted' => 1]);

           $success['platforms']=New PlatformResource($platform);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف السوق بنجاح','platform deleted successfully');
    }
}
