<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Websiteorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WebsiteorderResource;
use App\Http\Controllers\api\BaseController as BaseController;

class WebsiteorderController extends BaseController
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
        $success['Websiteorder']=WebsiteorderResource::collection(Websiteorder::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع الطلبات بنجاح','Websiteorder return successfully');
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
            'type'=>'required|string|max:255',
            'sevices'=>'exists:services,id'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $order_number=Websiteorder::orderBy('id', 'desc')->first();
        if(is_null($order_number)){
        $number = 0001;
        }else{

        $number=$order_number->order_number;
        $number= ((int) $number) +1;
        }
        $websiteorder = Websiteorder::create([
            'type' => $request->type,
            'order_number'=> str_pad($number, 4, '0', STR_PAD_LEFT),
            'store_id'=> $request->store_id,
          ]);
if($request->sevices!=null){
          $websiteorder->services_websiteorders()->attach(explode(',', $request->sevices),['status'=>$request->status]);
}
         $success['Websiteorders']=New WebsiteorderResource($websiteorder );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة الطلب بنجاح','Websiteorder Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function show(Websiteorder $websiteorder)
    {

        if (is_null($websiteorder) || $websiteorder->is_deleted==1){
               return $this->sendError("  الطلب غير موجودة","websiteorder is't exists");
               }
              $success['websiteorders']=New WebsiteorderResource($websiteorder);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض  الطلب  بنجاح','websiteorder showed successfully');
    }
    public function changeStatus($id)
    {
        $websiteorder = Websiteorder::query()->find($id);
        if (is_null($websiteorder) || $websiteorder->is_deleted==1){
         return $this->sendError("الطلب غير موجودة","websiteorder is't exists");
         }
        if($websiteorder->status === 'pending'){
            $websiteorder->update(['status' => 'accept']);
     }
    else{
        $websiteorder->update(['status' => 'reject']);
    }
        $success['websiteorders']=New WebsiteorderResource($websiteorder);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة الطلب بنجاح',' websiteorder status updared successfully');

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function edit(Websiteorder $websiteorder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Websiteorder $websiteorder)
    {
        if ( is_null($websiteorder) || $websiteorder->is_deleted==1){
            return $this->sendError(" الطلب غير موجودة"," websiteorder is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                'type'=>'required|string|max:255',
                'sevices'=>'exists:services,id'

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $websiteorder->update([
               'type' => $request->input('type'),


           ]);
         if($request->sevices!=null){
         $websiteorder->services_websiteorders()->sync(explode(',', $request->sevices),['status'=>$request->status]);
           }
           $success['websiteorders']=New WebsiteorderResource($websiteorder);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','websiteorder updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function destroy( $websiteorder)
    {
        $websiteorder =Websiteorder::query()->find($websiteorder);
        if (is_null($websiteorder)||$websiteorder->is_deleted==1){
            return $this->sendError(" الطلب غير موجودة","websiteorder is't exists");
            }
           $websiteorder->update(['is_deleted' => 1]);

           $success['websiteorders']=New WebsiteorderResource($websiteorder);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف  الطلب بنجاح','websiteorder deleted successfully');
    }
}
