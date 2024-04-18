<?php

namespace App\Http\Controllers\api\storeTemplate;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ReturnOrderResource;
use App\Models\ReturnOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturnOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index($id)
    {
        $success['ReturnOrders'] = ReturnOrderResource::collection(ReturnOrder::where('user_id', auth()->user()->id)->where('store_id', $id)->where('is_deleted', 0)->get());
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
            'data.*.product_id.*' => 'required|numeric',
            'data.*.option_id.*' => 'required|numeric',
            'data.*.price.*' => 'required|numeric',
            'data.*.qty.*' => 'required|numeric',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        if (isset($request->data)) {
            foreach ($request->data as $data) {
                $returnOrders[] = ReturnOrder::create([
                    'comment' => $request->input('comment', null),
                    'order_id' => $request->input('order_id', null),
                    'reason_txt' => $request->input('reason_txt', null),
                    'product_id' => $data['product_id'],
                    'option_id' =>  $data['option_id'],
                    'qty' => $data['qty'],
                    'price' => $data['price'],
                    'store_id' => $request->input('store_id', null),
                    'user_id' => auth()->user()->id,
                    'return_status' => 'pending',
                ]);
            }
        }

        $success['ReturnOrders'] =ReturnOrderResource::collection($returnOrders);
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
