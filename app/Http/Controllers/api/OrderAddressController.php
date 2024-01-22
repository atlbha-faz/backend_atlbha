<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\OrderAddressResource;
use App\Models\OrderAddress;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderAddressController extends BaseController
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
    public function index(Request $request)
    {
        if ($request->domain == null) {
            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'store is not exist');
        } else {
            $store = Store::where('domain', $request->domain)->first();
            $activeShippings = $store->shippingtypes()->get();
            $ids = array();
            $ids[]=null;
            if (count($activeShippings) > 0) {
                foreach ($activeShippings as $activeShipping) {
                    $ids[] = $activeShipping->id;
                }
            }
            $success['orderAddress'] = OrderAddressResource::collection(OrderAddress::whereIn('shippingtype_id', $ids)->where('user_id', auth()->user()->id)->get());

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع العناوين بنجاح', 'order Address return successfully');

        }
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
            'city' => 'required|string|max:255',
            'street_address' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'nullable|string',

            // 'type' => 'required|in:billing,shipping',
            // 'default_address' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $orderAddress = OrderAddress::create([
            'city' => $request->city,
            'street_address' => $request->street_address,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
            // 'shippingtype_id' => $request->shippingtype_id,
            'default_address' => $request->default_address,
            'user_id' => auth()->user()->id,

        ]);
        if ($orderAddress->default_address === '1') {

            $addresses = OrderAddress::where('user_id', auth()->user()->id)->whereNot('id', $orderAddress->id)->get();
            foreach ($addresses as $address) {
                $address->update([
                    'default_address' => 0,
                ]);
            }
        }
        $success['orderAddress'] = new OrderAddressResource($orderAddress);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة عنوان بنجاح', 'order Address Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderAddress = orderAddress::query()->find($id);
        if (is_null($orderAddress)) {
            return $this->sendError("العنوان غير موجود", " orderAddress is't exists");
        }
        $success['orderAddress'] = new OrderAddressResource($orderAddress);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض القسم بنجاح', 'orderAddress showed successfully');
    }

    public function show_default_address()
    {
        $orderAddress = orderAddress::where('user_id', auth()->user()->id)->where('default_address', 1)->first();
        if (is_null($orderAddress)) {
            return $this->sendError("العنوان غير موجود", " orderAddress is't exists");
        }
        $success['orderAddress'] = new OrderAddressResource($orderAddress);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض العنوان بنجاح', 'orderAddress showed successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $orderAddress = orderAddress::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (is_null($orderAddress)) {
            return $this->sendError("العنوان غير موجود", " orderAddress is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'city' => 'required|string|max:255',
            'street_address' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'nullable|string',
            // 'type' => 'required|in:billing,shipping',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $orderAddress->update([
            'discount_type' => $request->input('discount_type'),
            'city' => $request->input('city'),
            'street_address' => $request->input('street_address'),
            'district' => $request->input('district'),
            'postal_code' => $request->input('postal_code'),
            // 'shippingtype_id' => $request->input('shippingtype_id'),
            // 'type' => $request->input('type'),
            'default_address' => $request->default_address,
            'user_id' => auth()->user()->id,
        ]);
        if ($orderAddress->default_address === '1') {

            $addresses = OrderAddress::where('user_id', auth()->user()->id)->whereNot('id', $orderAddress->id)->get();
            foreach ($addresses as $address) {
                $address->update([
                    'default_address' => 0,
                ]);
            }
        }
        $success['orderAddresss'] = new OrderAddressResource($orderAddress);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'orderAddress updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $orderAddress = OrderAddress::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (is_null($orderAddress)) {
            return $this->sendError("العنوان غير موجودة", "order Address is't exists");

        }
        $orderAddress->delete();
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف العنوان بنجاح', ' order Address deleted successfully');

    }
    public function setDefaultAddress($id)
    {
        $orderAddres = orderAddress::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (is_null($orderAddres)) {
            return $this->sendError("العنوان غير موجود", " orderAddress is't exists");
        }

        if ($orderAddres->default_address == 0) {
            $orderAddres->update([
                'default_address' => 1,
            ]);
            $addresses = OrderAddress::where('user_id', auth()->user()->id)->whereNot('id', $id)->get();
            foreach ($addresses as $address) {
                $address->update([
                    'default_address' => 0,
                ]);
            }
        }
        //  else {
        //     $product->update(['status' => 'active']);
        // }
        $success['orderAddresss'] = new OrderAddressResource($orderAddres);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم التعديل بنجاح', 'orderAddress updated successfully');

    }
}
