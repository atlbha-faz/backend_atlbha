<?php


namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Shippingtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ShippingtypeResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ShippingtypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $success['shippingtypes']=ShippingtypeResource::collection(Shippingtype::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع شركات الشحن بنجاح','Shippingtype return successfully');
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
        $shippingtype = Shippingtype::create([
            'name' => $request->name,


          ]);


         $success['shippingtypes']=New ShippingtypeResource( $shippingtype);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة شركة شحن بنجاح',' Shippingtype Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */
    public function show($shippingtype)
     {
        $shippingtype = Shippingtype::query()->find($shippingtype);
        if (is_null($shippingtype) || $shippingtype->is_deleted==1){
        return $this->sendError("شركة الشحن غير موجودة","shippingtype is't exists");
        }


       $success['shippingtypes']=New ShippingtypeResource($shippingtype);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض الشركة بنجاح','shippingtype showed successfully');
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */
    public function edit(Shippingtype $shippingtype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shippingtype $shippingtype)
        {
         if (is_null($shippingtype) || $shippingtype->is_deleted==1){
         return $this->sendError("شركة الشحن غير موجودة","shippingtype is't exists");
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
         $shippingtype->update([
            'name' => $request->input('name'),



         ]);
         //$country->fill($request->post())->update();
            $success['shippingtypes']=New ShippingtypeResource($shippingtype);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','shippingtype updated successfully');
    }


     public function changeStatus($id)
    {
        $shippingtype = Shippingtype::query()->find($id);
         if (is_null($shippingtype) || $shippingtype->is_deleted==1){
         return $this->sendError("شركة الشحن غير موجودة","shippingtype is't exists");
         }

        if($shippingtype->status === 'active'){
        $shippingtype->update(['status' => 'not_active']);
        }
        else{
        $shippingtype->update(['status' => 'active']);
        }
        $success['shippingtypes']=New ShippingtypeResource($shippingtype);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة شركة الدفع بنجاح','shipping type updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */
    public function destroy($shippingtype)
     {
       $shippingtype = Shippingtype::query()->find($shippingtype);
         if (is_null($shippingtype) || $shippingtype->is_deleted==1){
         return $this->sendError("شركة الشحن غير موجودة","shippingtype is't exists");
         }
        $shippingtype->update(['is_deleted' => 1]);

        $success['shippingtypes']=New ShippingtypeResource($shippingtype);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف شركة الشحن بنجاح','shippingtype deleted successfully');
    }
}