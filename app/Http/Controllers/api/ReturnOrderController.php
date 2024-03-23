<?php

namespace App\Http\Controllers\api;

use App\Models\ReturnOrder;

use Illuminate\Http\Request;
use App\Http\Resources\ReturnOrderResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ReturnOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index($id)
    {
        $success['ReturnOrders'] =  ReturnOrderResource::collection(ReturnOrder::where('user_id',auth()->user()->id)->where('store_id',$id)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'ReturnOrders showed successfully');
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
        $validator = Validator::make($input, [
            'comment' => 'string|max:255',
            'order_id' => 'required|numeric',
            'reason_txt' => 'required|string',
            'store_id' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $returnOrder = ReturnOrder::create([
            'comment' => $request->input('comment'),
            'order_id' => $request->input('order_id'),
            'reason_txt' => $request->input('reason_txt',null),
            'option_id' => $request->input('option_id',null),
            'product_id' => $request->input('product_id',null),
            'store_id' => $request->input('store_id'),
            'user_id' => auth()->user()->id,
            'return_status'=>'pending'
        ]);

        $success['ReturnOrders'] = new ReturnOrderResource($returnOrder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة طلب استرجاع بنجاح', ' Added return_status successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function show(returnOrder $returnOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(returnOrder $returnOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, returnOrder $returnOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(returnOrder $returnOrder)
    {
        //
    }
}
