<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Option;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\OptionResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class OptionController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
      {
        $success['options']=OptionResource::collection(Option::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الخيارات بنجاح','options return successfully');
    }

 public function optionsProduct($product_id)
   {
     $option =Option::where('product_id',$product_id)->first();
        if (is_null($option) || $option->is_deleted==1){
        return $this->sendError("لايوجد خيارات لهذا المنتج","options is't exists");
        }
        $success['options']=OptionResource::collection(Option::where('is_deleted',0)->where('product_id',$product_id)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الخيارات بنجاح','options return successfully');
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
            'data'=>'required|array',
            'data.*.type'=>'required|in:brand,color,wight,size',
            'data.*.title'=>'required|string',
            'data.*.value'=>'required|array',
            'data.*.product_id'=>'required|exists:products,id',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
         foreach($request->data as $data)
    {
//$request->input('name', []);
        $option= new Option([
            'type' => $data['type'],
            'title' => $data['title'],
            'value' => $data['value'],
            'product_id' => $data['product_id']
          ]);

        $option->save();
        $options[]=$option;
        }
         $success['options']=OptionResource::collection($options);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة متجر بنجاح',' store Added successfully');



    return response()->json('Successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function show($option)
   {
        $option =Option::query()->find($option);
        if (is_null($option) || $option->is_deleted==1){
        return $this->sendError("الخيار غير موجود","option is't exists");
        }


       $success['options']=New optionResource($option);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض الخيار  بنجاح','option showed successfully');

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function edit(Option $option)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */

   public function update(Request $request, $product_id)
  {


    //    dd($options_id);

    $input = $request->all();
    $validator =  Validator::make($input, [
      'data' => 'required|array',
      'data.*.type' => 'required|in:brand,color,wight,size',
      'data.*.title' => 'required|string',
      'data.*.value' => 'required|array',
      'data.*.id' => 'nullable|numeric',
    ]);
    if ($validator->fails()) {
      # code...
      return $this->sendError(null, $validator->errors());
    }
    $option = Option::where('product_id', $product_id);


    // dd($request->$data['id']);
    $options_id = Option::where('product_id', $product_id)->pluck('id')->toArray();
    foreach ($options_id as $oid) {
      if (!(in_array($oid, array_column($request->data, 'id')))) {
        $option = Option::query()->find($oid);
        $option->update(['is_deleted' => 1]);
      }
    }

    foreach ($request->data as $data) {
      $options[] = Option::updateOrCreate([
        'id' => $data['id'],
        'product_id' => $product_id,
        'is_deleted' => 0,
      ], [
        'type' => $data['type'],
        'title' => $data['title'],
        'value' => $data['value'],
        'product_id' => $product_id
      ]);
    }


    //  dd($option->id);


    $success['options'] = OptionResource::collection($options);
    $success['status'] = 200;

    return $this->sendResponse($success, 'تم التعديل بنجاح', 'option updated successfully');
  }

       public function changeStatus($id)
    {
        $option = Option::query()->find($id);
       if (is_null($option) || $option->is_deleted==1){
         return $this->sendError(" الخيار غير موجود","option is't exists");
         }

        if($option->status === 'active'){
        $option->update(['status' => 'not_active']);
        }
        else{
        $option->update(['status' => 'active']);
        }
        $success['options']=New OptionResource($option);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة الخيار بنجاح','option updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function destroy($option)
   {
       $option = Option::query()->find($option);
         if (is_null($option) || $option->is_deleted==1){
         return $this->sendError("الخيار غير موجود","option is't exists");
         }
        $option->update(['is_deleted' => 1]);

        $success['option']=New OptionResource($option);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف الخيار بنجاح','option deleted successfully');
    }
}
