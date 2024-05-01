<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ShippingtypeResource;
use App\Models\Shippingtype;
use App\Models\shippingtype_store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingtypeController extends BaseController
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

        $success['shippingtypes'] = ShippingtypeResource::collection(Shippingtype::where('is_deleted', 0)->where('status', 'active')->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع شركات الشحن بنجاح', 'Shippingtype return successfully');
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
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */
    public function show($shippingtype)
    {
        $shippingtype = Shippingtype::query()->find($shippingtype);
        if (is_null($shippingtype) || $shippingtype->is_deleted != 0) {
            return $this->sendError("شركة الشحن غير موجودة", "shippingtype is't exists");
        }

        $success['shippingtype'] = new ShippingtypeResource($shippingtype);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الشركة بنجاح', 'shippingtype showed successfully');
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
   

    public function changeStatus($id, Request $request)
    {
        $shippingtype = Shippingtype::query()->find($id);
        if (is_null($shippingtype) || $shippingtype->is_deleted != 0 || $shippingtype->status == "not_active") {
            return $this->sendError("شركة الشحن غير موجودة", "shippingtype is't exists");
        }
        $shippingtype = shippingtype_store::where('shippingtype_id', $id)->where('store_id', auth()->user()->store_id)->first();

        if ($shippingtype != null) {
            $shippingtype->delete();
        } else {
            $input = $request->all();
            $validator = Validator::make($input, [

                'price' => ['nullable', 'numeric'],
                'time' => ['nullable', 'numeric'],
                'overprice' => ['nullable', 'numeric']
            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }
            // Check if there's at least one active shipping company
                if($id == 1){
                    $request->price=30;
                    $request->time=3;
                    $request->overprice=3;
                }
            $shippingtype = shippingtype_store::create([
                'shippingtype_id' => $id,
                'store_id' => auth()->user()->store_id,
                'price' => $request->price !== null ? $request->price : 35,
                'time' => $request->time !== null ? $request->time : 1,
                'overprice' => $request->overprice !== null ? $request->overprice : 1,
            ]);
            $success['shippingtypes'] = $shippingtype;

        }

        // }
        $success['shippingtypess'] = ShippingtypeResource::collection(Shippingtype::where('is_deleted', 0)->where('status', 'active')->orderByDesc('created_at')->get());
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعديل حالة طريقة الشحن بنجاح', 'shipping type updated successfully');
    }
    public function updatePrice(Request $request, $id)
    {
        $shippingtypeCompany = Shippingtype::query()->find($id);
        $shippingtype = shippingtype_store::where('shippingtype_id', $id)->where('store_id', auth()->user()->store_id)->first();
        if (is_null($shippingtype)) {
            return $this->sendError("شركة الشحن غير موجودة", "shippingtype is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [

            'price' => ['nullable', 'numeric'],
            'time' => ['nullable', 'numeric'],
            'overprice' => ['nullable', 'numeric']
        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $shippingtype->update([
            'price' => $request->price,
            'time' => $request->time,
            'overprice'=>$request->overprice,
        ]);
        $success['shippingtypes'] = new ShippingtypeResource($shippingtypeCompany);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعديل حالة طريقة  الشحن بنجاح', 'shipping type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */

}
