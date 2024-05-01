<?php

namespace App\Http\Controllers\api\storeTemplate;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnOrder;
use Illuminate\Http\Request;
use App\Http\Requests\ReturnOrderRequest;
use Illuminate\Support\Facades\Validator;
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
        $return= Order::with('returnOrders')->where('is_deleted', 0)->whereHas('items', function ($q) use($id) {
            $q->where('store_id', $id)->where('is_return', 1);
        })->where('user_id', auth()->user()->id)->where('store_id', $id)->first();
        if (is_null($return) ) {
            return $this->sendError("لا يوجد طلبات مسترجعة", "return is't exists");
        }
        $success['ReturnOrders'] = ReturnOrderResource::collection(Order::with('returnOrders')->where('is_deleted', 0)->whereHas('items', function ($q) use($id) {
            $q->where('store_id', $id)->where('is_return', 1);
        })->where('user_id', auth()->user()->id)->where('store_id', $id)->get());
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
    public function store(ReturnOrderRequest $request)
    {

        $input = $request->all();
        if (isset($request->data)) {
            foreach ($request->data as $data) {
                $returnOrders[] = ReturnOrder::create([
                    'comment' => $request->input('comment', null),
                    'order_id' => $request->input('order_id', null),
                    'return_reason_id' => $request->input('return_reason_id', null),
                    'order_item_id' => $data['order_item_id'],
                    'qty' => $data['qty'],
                    'store_id' => $request->input('store_id', null),
                    'return_status' => 'pending',
                ]);
                $order_item=OrderItem::where('id', $data['order_item_id'])->first();
                $order_item->update([
                    'is_return' => 1
                ]); 
            }
        }

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة طلب استرجاع بنجاح', ' Added return_status successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function show( $returnOrder)
    {
        $return= Order::with('returnOrders')->where('id',$returnOrder)->where('is_deleted', 0)->whereHas('items', function ($q) {
            $q->where('is_return', 1);
        })->where('user_id', auth()->user()->id)->first();
        if (is_null($return) ) {
            return $this->sendError("لا يوجد طلب مسترجع", "return is't exists");
        }
        $success['ReturnOrder'] = new ReturnOrderResource(Order::with('returnOrders')->where('id',$returnOrder)->where('is_deleted', 0)->whereHas('items', function ($q) {
            $q->where('is_return', 1);
        })->where('user_id', auth()->user()->id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'ReturnOrders showed successfully');
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
