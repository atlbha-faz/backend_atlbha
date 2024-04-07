<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\OrderOrderAddress;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\shippingtype_store;
use App\Services\AramexService;
use App\Services\FatoorahServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use in;

class OrderController extends BaseController
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
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $success['new'] = Order::where('store_id', auth()->user()->store_id)->where('order_status', 'new')->count();
        $success['completed'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('order_status', 'completed');
        })->count();

        $success['not_completed'] = Order::where('store_id', auth()->user()->store_id)->where('order_status', 'not_completed')->count();
        $success['canceled'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('order_status', 'canceled');
        })->count();

        $success['all'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->count();
        $data = OrderResource::collection(Order::with(['user', 'shipping', 'shippingtype', 'items' => function ($query) {
            $query->select('id');
        }])->where('store_id', auth()->user()->store_id)->orderByDesc('id')->select(['id', 'user_id', 'shippingtype_id', 'total_price', 'quantity', 'order_status','payment_status', 'created_at'])->paginate($count));
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();

        $success['orders'] = $data;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'Orders return successfully');
    }

    public function show($order)
    {
        $order = Order::where('id', $order)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->first();
        if (is_null($order)) {
            return $this->sendError("'الطلب غير موجود", "Order is't exists");
        }

        $success['orders'] = new OrderResource($order);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الطلب بنجاح', 'Order showed successfully');
    }

    public function update(Request $request, $order)
    {
        $order = Order::where('id', $order)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->first();
        if (is_null($order)) {
            return $this->sendError("'الطلب غير موجود", "Order is't exists");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required|in:new,completed,delivery_in_progress,ready,refund,canceled',

            'city' => 'required_if:status,==,ready',
            'street_address' => 'required_if:status,==,ready',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        if ($order->order_status == "canceled") {

            $order->update([
                'order_status' => $request->input('status'),
            ]);
            foreach ($order->items as $orderItem) {
                $orderItem->update([
                    'order_status' => $request->input('status'),
                ]);
            }

            $success['orders'] = new OrderResource($order);
            return $this->sendResponse($success, 'تم التعديل بنجاح', 'Order updated successfully');

        } else {
            if ($request->status === "ready") {
                $orderAddress = OrderOrderAddress::where('order_id', $order->id)->where('type', 'shipping')->value('order_address_id');
                $address = OrderAddress::where('id', $orderAddress)->first();
                $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
                //  if( $order->payment_status=='paid'|| $order->cod ==1)
                //  {
                if ($order->shippingtype->id == 1) {
                    $data=[
                        "shipper_line1"=>$request->street_address,
                        "shipper_line2"=>$request->street_address,
                        "shipper_city"=>$request->city,
                        "shipper_district"=>$request->district,
                        "shipper_name"=>auth()->user()->name,
                        "shipper_comany"=>auth()->user()->store->store_name,
                        "shipper_name"=>auth()->user()->name,
                        "shipper_phonenumber"=>auth()->user()->phonenumber,
                        "shipper_email"=>auth()->user()->email,
                        "order_id"=> $order->id

                    ];
                    $aramex = new AramexService();
                    return $aramex->createOrder($data);
                }
            }
            if ($request->status === "refund") {
                $orderAddress = OrderOrderAddress::where('order_id', $order->id)->where('type', 'shipping')->value('order_address_id');
                $address = OrderAddress::where('id', $orderAddress)->first();
                $shipping = Shipping::where('order_id', $order->id)->first();

                $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
                if ($shipping == null) {
                    return $this->sendError("لايمكن استرجاع الطلب", "shipping is't exists");
                }
                if ($order->shippingtype->id == 1) {

                    $json = '{
                        "ClientInfo": {
                            "UserName": "armx.ruh.it@gmail.com",
                            "Password": "YUre@9982",
                            "Version": "v1",
                            "AccountNumber": "117620",
                            "AccountPin": "553654",
                            "AccountEntity": "JED",
                            "AccountCountryCode": "SA",
                            "Source": 24
                        },
                        "LabelInfo": {
                            "ReportID": 9729,
                            "ReportType": "URL"
                        },
                        "Shipments": [
                            {
                                "Reference1": "' . $order->id . '",
                                "Reference2": "",
                                "Reference3": "",
                                "Shipper": {
                                    "Reference1": "",
                                    "Reference2": "",
                                    "AccountNumber": "117620",
                                    "PartyAddress": {
                                        "Line1": "' . $address->street_address . '",
                                        "Line2": "' . $address->street_address . '",
                                        "Line3": "",
                                        "City": "' . $address->city . '",
                                        "StateOrProvinceCode": "",
                                        "PostCode": "",
                                        "CountryCode": "SA",
                                        "Longitude": 0,
                                        "Latitude": 0,
                                        "BuildingNumber": null,
                                        "BuildingName": null,
                                        "Floor": null,
                                        "Apartment": null,
                                        "POBox": null,
                                        "Description": null
                                    },
                                    "Contact": {
                                        "Department": "",
                                        "PersonName": "' . $order->user->name . '",
                                        "Title": "",
                                        "CompanyName": "' . $order->user->name . '",
                                        "PhoneNumber1": "' . $order->user->phonenumber . '",
                                        "PhoneNumber1Ext": "",
                                        "PhoneNumber2": "",
                                        "PhoneNumber2Ext": "",
                                        "FaxNumber": "",
                                        "CellPhone": "' . $order->user->phonenumber . '",
                                        "EmailAddress": "' . $order->user->email . '",
                                        "Type": ""
                                    }
                                },
                                "Consignee": {
                                    "Reference1": "",
                                    "Reference2": "",
                                    "AccountNumber": "",
                                    "PartyAddress": {
                                        "Line1": "' . $shipping->streetaddress . '",
                                        "Line2": "' . $shipping->streetaddress . '",
                                        "Line3": "",
                                        "City": "' . $shipping->city . '",
                                        "StateOrProvinceCode": "",
                                        "PostCode": "",
                                        "CountryCode": "SA",
                                        "Longitude": 0,
                                        "Latitude": 0,
                                        "BuildingNumber": "",
                                        "BuildingName": "",
                                        "Floor": "",
                                        "Apartment": "",
                                        "POBox": null,
                                        "Description": ""
                                    },
                                    "Contact": {
                                        "Department": "",
                                        "PersonName": "' . auth()->user()->name . '",
                                        "CompanyName": "' . auth()->user()->store->store_name . '",
                                        "PhoneNumber1": "' . auth()->user()->phonenumber . '",
                                        "PhoneNumber1Ext": "",
                                        "PhoneNumber2": "",
                                        "PhoneNumber2Ext": "",
                                        "FaxNumber": "",
                                        "CellPhone": "' . auth()->user()->phonenumber . '",
                                        "EmailAddress": "' . auth()->user()->email . '",
                                        "Type": ""
                                    }
                                },
                                "ThirdParty": {
                                    "Reference1": "",
                                    "Reference2": "",
                                    "AccountNumber": "",
                                    "PartyAddress": {
                                        "Line1": "",
                                        "Line2": "",
                                        "Line3": "",
                                        "City": "",
                                        "StateOrProvinceCode": "",
                                        "PostCode": "",
                                        "CountryCode": "",
                                        "Longitude": 0,
                                        "Latitude": 0,
                                        "BuildingNumber": null,
                                        "BuildingName": null,
                                        "Floor": null,
                                        "Apartment": null,
                                        "POBox": null,
                                        "Description": null
                                    },
                                    "Contact": {
                                        "Department": "",
                                        "PersonName": "",
                                        "Title": "",
                                        "CompanyName": "",
                                        "PhoneNumber1": "",
                                        "PhoneNumber1Ext": "",
                                        "PhoneNumber2": "",
                                        "PhoneNumber2Ext": "",
                                        "FaxNumber": "",
                                        "CellPhone": "",
                                        "EmailAddress": "",
                                        "Type": ""
                                    }
                                },
                                "ShippingDateTime": "\\/Date(' . $shippingDate . ')\\/",
                                "DueDate": "\\/Date(' . $shippingDate . ')\\/",
                                "Comments": "",
                                "PickupLocation": "",
                                "OperationsInstructions": "",
                                "AccountingInstrcutions": "",
                                "Details": {
                                    "Dimensions": null,
                                    "ActualWeight": {
                                        "Unit": "KG",
                                        "Value": 0.5
                                    },
                                    "ChargeableWeight": null,
                                    "DescriptionOfGoods": "",
                                    "GoodsOriginCountry": "SA",
                                    "NumberOfPieces": 1,
                                    "ProductGroup": "DOM",
                                    "ProductType": "Rtn",
                                    "PaymentType": "P",
                                    "PaymentOptions": "",
                                    "CustomsValueAmount": null,
                                    "CashOnDeliveryAmount": {
                                        "CurrencyCode": "SAR",
                                        "Value": 1
                                    },
                                    "InsuranceAmount": null,
                                    "CashAdditionalAmount": null,
                                    "CashAdditionalAmountDescription": "",
                                    "CollectAmount": null,
                                    "Services": "CODS",
                                    "Items": []
                                },
                                "Attachments": [],
                                "ForeignHAWB": "",
                                "TransportType ": 0,
                                "PickupGUID": "a620ba04-2d15-4294-991a-051ba56fae45",
                                "Number": null,
                                "ScheduledDelivery": null
                            }
                        ],
                        "Transaction": {
                            "Reference1": "",
                            "Reference2": "",
                            "Reference3": "",
                            "Reference4": "",
                            "Reference5": ""
                        }
                    }';

                    $aramex = new AramexService();

                    $arData = $amex->buildRequest('POST', $json);

                    if ($arData->HasErrors == false) {
                        $ship_id = $arData->Shipments[0]->ID;
                        $url = $arData->Shipments[0]->ShipmentLabel->LabelURL;
                        $order->update([
                            'order_status' => $request->input('status'),
                        ]);
                        foreach ($order->items as $orderItem) {
                            $orderItem->update([
                                'order_status' => $request->input('status'),
                            ]);
                        }
                        $shipping = Shipping::create([
                            'shipping_id' => $ship_id,
                            // 'track_id' => $track_id,
                            'sticker' => $url,
                            'description' => $order->description,
                            'quantity' => $order->quantity,
                            'price' => $order->total_price,
                            'weight' => $order->weight,
                            'district' => $request->district,
                            'city' => $request->city,
                            'streetaddress' => $request->street_address,
                            'customer_id' => $order->user_id,
                            'shippingtype_id' => $order->shippingtype_id,
                            'order_id' => $order->id,
                            'shipping_status' => $order->order_status,
                            'store_id' => $order->store_id,
                            'cashondelivery' => $order->cashondelivery,
                        ]);

                        $payment = Payment::where('orderID', $order->id)->first();
                        if ($payment != null) {
                            $shipping_price = shippingtype_store::where('shippingtype_id', $order->shippingtype_id)->where('store_id', auth()->user()->store_id)->first();
                            if ($shipping_price == null) {
                                $shipping_price = 35;
                                $extraprice = 2;
                            } else {
                                $overprice = $shipping_price->overprice;
                                $shipping_price = $shipping_price->price;
                                $extraprice = $overprice;
                            }if ($order->weight > 15) {
                                $default_extra_price = ($order->weight - 15) * 2;
                                $extra_shipping_price = ($order->weight - 15) * $extraprice;
                            } else {
                                $extra_shipping_price = 0;
                                $default_extra_price = 0;
                            }
                            $total_price_without_shipping = ($order->total_price) - ($shipping_price) - ($extra_shipping_price);
                            $data = [

                                "Key" => $payment->paymentTransectionID,
                                "KeyType" => "invoiceid",
                                "RefundChargeOnCustomer" => false,
                                "ServiceChargeOnCustomer" => false,
                                "Amount" => $total_price_without_shipping,
                                "Comment" => "refund to the customer",
                                "AmountDeductedFromSupplier" => 0,
                                "CurrencyIso" => "SA",
                            ];

                            $supplier = new FatoorahServices();
                            $supplierCode = $supplier->buildRequest('v2/MakeRefund', 'POST', $data);

                            if ($supplierCode->IsSuccess == false) {
                                return $this->sendError("خطأ في الارجاع", $supplierCode->ValidationErrors[0]->Error);
                            } else {
                                $success['test'] = $supplierCode;
                            }

                        }
                        $success['shipping'] = new shippingResource($shipping);

                    } else {

                        $success['shippingCompany'] = $arData;
                        return $this->sendResponse($success, "خطأ في البيانات المدخلة", "message");
                    }
                }
            }
        }
    }

    public function deleteAll(Request $request)
    {

        $orders = Order::whereIn('id', $request->id)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->get();
        foreach ($orders as $order) {
            if (is_null($order)) {
                return $this->sendError("الطلب غير موجود", " Order is't exists");
            }
            $items = OrderItem::where('store_id', auth()->user()->store_id)->where('order_id', $order->id)->get();
            foreach ($items as $item) {
                $item->delete();
            }
        }
        $success['orders'] = OrderResource::collection($orders);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الطلبات بنجاح', 'Order deleted successfully');
    }
    public function searchOrder(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $orders = Order::whereHas('user' ,function ($userQuery) use ($query) {
            $userQuery->where('name', 'like', "%$query%");
        })->orWhereHas( 'shippingtype' , function ($shippingtypeQuery) use ($query) {
            $shippingtypeQuery->where('name', 'like', "%$query%");
        })->orWhereHas('shipping',function ($shippingQuery) use ($query) {
            $shippingQuery->where('track_id', 'like', "%$query%");
        })->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)
            ->orderBy('created_at', 'desc')->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $orders->total();
        $success['page_count'] = $orders->lastPage();
        $success['current_page'] = $orders->currentPage();
        $success['orders'] = OrderResource::collection($orders);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'orders Information returned successfully');

    }

}
