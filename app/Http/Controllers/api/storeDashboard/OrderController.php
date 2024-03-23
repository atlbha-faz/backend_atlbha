<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\OrderOrderAddress;
use App\Models\Shipping;
use App\Services\AramexService;
use App\Services\FatoorahServices;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Paymenttype;
use App\Models\shippingtype_store;
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

        if ($request->has('page')) {
            $data = OrderResource::collection(Order::with(['user', 'shipping', 'shippingtype', 'items' => function ($query) {
                $query->select('id');
            }])->where('store_id', auth()->user()->store_id)->orderByDesc('id')->select(['id', 'user_id', 'shippingtype_id', 'total_price', 'quantity', 'order_status', 'created_at'])->paginate(8));
            $success['page_count'] = $data->lastPage();

        } else {

            $data = OrderResource::collection(Order::with(['user', 'shipping', 'shippingtype', 'items' => function ($query) {
                $query->select('id');
            }])->where('store_id', auth()->user()->store_id)->orderByDesc('id')->get(['id', 'user_id', 'shippingtype_id', 'total_price', 'quantity', 'order_status']));

        }
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
                                        "Line1": "' . $request->street_address . '",
                                        "Line2": "' . $request->street_address . '",
                                        "Line3": "",
                                        "City": "' . $request->city . '",
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
                                        "PersonName": "' . auth()->user()->name . '",
                                        "Title": "",
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
                                "Consignee": {
                                    "Reference1": "",
                                    "Reference2": "",
                                    "AccountNumber": "",
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
                                        "BuildingNumber": "",
                                        "BuildingName": "",
                                        "Floor": "",
                                        "Apartment": "",
                                        "POBox": null,
                                        "Description": ""
                                    },
                                    "Contact": {
                                        "Department": "",
                                        "PersonName": "' . $order->user->name . '",
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
                                    "ProductType": "cds",
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

                    $ar = new AramexService();

                    $arData = $ar->createOrder($json);
                    if ($arData->HasErrors == false) {
                        $success['shippingCompany'] = $arData;
                    }

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
                        $success['shipping'] = new shippingResource($shipping);
                    } else {

                        //             $ship_id = null;
                        //             $track_id = null;
                        $success['shippingCompany'] = $arData;
                        return $this->sendResponse($success, "خطأ في البيانات المدخلة", "message");
                    }
                } elseif ($order->shippingtype->id == 2) {

                    //         $data = array(
                    //             'userId' => env('GOTEX_UserId_KEY'),
                    //             'apiKey' => env('GOTEX_API_KEY'),
                    //             'c_name' => $order->user->name,
                    //             'c_mobile' => $order->user->phonenumber,
                    //             'c_city' => $address->city,
                    //             'c_streetaddress' => $address->street_address,
                    //             'p_name' => auth()->user()->store->store_name,
                    //             'p_mobile' => auth()->user()->phonenumber,
                    //             'p_city' => $request->city,
                    //             'p_streetaddress' => $request->street_address,
                    //             'quantity' => 1,
                    //             'weight' => $order->weight,
                    //             'description' => $order->description,
                    //             'cashondelivery' => 0,
                    //             'cashonpickup' => 0,
                    //             'Cod' => true,
                    //             'shipmentValue' => $order->total_price,

                    //         );
                    //         $Saee = new SaeeService();
                    //         $SaeeData = $Saee->createOrder($data);
                    //         $ship = $SaeeData;

                    //         $success['shippingCompany'] = $ship;
                    //         if (isset($ship->data)) {
                    //             $ship_id = $ship->data->_id;
                    //             $track_id = $ship->data->data->waybill;
                    //             $order->update([
                    //                 'order_status' => $request->input('status'),
                    //             ]);
                    //             foreach ($order->items as $orderItem) {
                    //                 $orderItem->update([
                    //                     'order_status' => $request->input('status'),
                    //                 ]);
                    //             }
                    //             $shipping = Shipping::create([
                    //                 'shipping_id' => $ship_id,
                    //                 'track_id' => $track_id,
                    //                 'description' => $order->description,
                    //                 'quantity' => $order->quantity,
                    //                 'price' => $order->total_price,
                    //                 'weight' => $order->weight,
                    //                 'district' => $request->district,
                    //                 'city' => $request->city,
                    //                 'streetaddress' => $request->street_address,
                    //                 'customer_id' => $order->user_id,
                    //                 'shippingtype_id' => $order->shippingtype_id,
                    //                 'order_id' => $order->id,
                    //                 'shipping_status' => $order->order_status,
                    //                 'store_id' => $order->store_id,
                    //                 'cashondelivery' => $order->cashondelivery,
                    //             ]);
                    //             $success['shipping'] = new shippingResource($shipping);
                    //         } else {
                    //             $ship_id = null;
                    //             $track_id = null;
                    //             $success['shippingCompany'] = $ship->msg;
                    //             return $this->sendResponse($success, "خطأ في البيانات المدخلة", "message");
                    //         }
                } elseif ($order->shippingtype->id == 3) {
                    //         //imile company
                    //         $client = array(
                    //             'userId' => env('GOTEX_UserId_KEY'),
                    //             'apiKey' => env('GOTEX_API_KEY'),
                    //             'companyName' => auth()->user()->store->domain,
                    //             'contacts' => auth()->user()->name,
                    //             'country' => "KSA",
                    //             'city' => $request->city,
                    //             'address' => $request->street_address,
                    //             'phone' => substr(auth()->user()->phonenumber, 1),
                    //             'Email' => auth()->user()->email,
                    //             'backupPhone' => "",
                    //             'Attentions' => "this is consignor address",
                    //         );
                    //         $imile = new ImileService();
                    //         $imileClient = $imile->addClient($client);

                    //         $data = array(
                    //             'userId' => env('GOTEX_UserId_KEY'),
                    //             'apiKey' => env('GOTEX_API_KEY'),
                    //             'p_company' => auth()->user()->store->domain,
                    //             'c_company' => $order->user->name,
                    //             'c_name' => $order->user->name,
                    //             'c_mobile' => $order->user->phonenumber,
                    //             'c_street' => $address->street_address,
                    //             'c_city' => $address->city,
                    //             'c_address' => $request->street_address,
                    //             'skuName' => $order->description,
                    //             'skuTotal' => 1,
                    //             'weight' => $order->weight,
                    //             'cod' => true,
                    //             'shipmentValue' => $order->total_price,
                    //         );

                    //         $imileData = $imile->createOrder($data);
                    //         $ship = $imileData;
                    //         $success['shippingCompany'] = $ship;
                    //         if (isset($ship->data)) {
                    //             $ship_id = $ship->data->_id;
                    //             $track_id = $ship->data->data->traceId;
                    //             $order->update([
                    //                 'order_status' => $request->input('status'),
                    //             ]);
                    //             foreach ($order->items as $orderItem) {
                    //                 $orderItem->update([
                    //                     'order_status' => $request->input('status'),
                    //                 ]);
                    //             }
                    //             $shipping = Shipping::create([
                    //                 'shipping_id' => $ship_id,
                    //                 'track_id' => $track_id,
                    //                 'description' => $order->description,
                    //                 'quantity' => $order->quantity,
                    //                 'price' => $order->total_price,
                    //                 'weight' => $order->weight,
                    //                 'district' => $request->district,
                    //                 'city' => $request->city,
                    //                 'streetaddress' => $request->street_address,
                    //                 'customer_id' => $order->user_id,
                    //                 'shippingtype_id' => $order->shippingtype_id,
                    //                 'order_id' => $order->id,
                    //                 'shipping_status' => $order->order_status,
                    //                 'store_id' => $order->store_id,
                    //                 'cashondelivery' => $order->cashondelivery,
                    //             ]);
                    //             $success['shipping'] = new shippingResource($shipping);
                    //         } else {
                    //             $ship_id = null;
                    //             $track_id = null;
                    //             $success['shippingCompany'] = $ship->msg->message;
                    //             return $this->sendResponse($success, "خطأ في البيانات المدخلة", "message");
                    //         }
                } elseif ($order->shippingtype->id == 4) {
                    //         $data = array(
                    //             'userId' => env('GOTEX_UserId_KEY'),
                    //             'apiKey' => env('GOTEX_API_KEY'),
                    //             're_name' => $order->user->name,
                    //             're_mobile' => $order->user->phonenumber,
                    //             're_prov' => $address->district,
                    //             're_city' => $address->city,
                    //             's_name' => auth()->user()->store->store_name,
                    //             's_mobile' => auth()->user()->phonenumber,
                    //             's_prov' => $request->district,
                    //             's_city' => $request->city,
                    //             'weight' => $order->weight,
                    //             'description' => $order->description,
                    //             'totalQuantity' => 1,
                    //             'goodsType' => 'ITN4',
                    //             'Cod' => true,
                    //             'shipmentValue' => $order->total_price,
                    //         );
                    //         $JT = new JTService();
                    //         $JTData = $JT->createOrder($data);
                    //         $ship = $JTData;
                    //         $success['shippingCompany'] = $ship;
                    //         if (isset($ship->data)) {
                    //             $ship_id = $ship->data->_id;
                    //             $track_id = $ship->data->data->data->billCode;
                    //             $order->update([
                    //                 'order_status' => $request->input('status'),
                    //             ]);
                    //             foreach ($order->items as $orderItem) {
                    //                 $orderItem->update([
                    //                     'order_status' => $request->input('status'),
                    //                 ]);
                    //             }
                    //             $shipping = Shipping::create([
                    //                 'shipping_id' => $ship_id,
                    //                 'track_id' => $track_id,
                    //                 'description' => $order->description,
                    //                 'quantity' => $order->quantity,
                    //                 'price' => $order->total_price,
                    //                 'weight' => $order->weight,
                    //                 'district' => $request->district,
                    //                 'city' => $request->city,
                    //                 'streetaddress' => $request->street_address,
                    //                 'customer_id' => $order->user_id,
                    //                 'shippingtype_id' => $order->shippingtype_id,
                    //                 'order_id' => $order->id,
                    //                 'shipping_status' => $order->order_status,
                    //                 'store_id' => $order->store_id,
                    //                 'cashondelivery' => $order->cashondelivery,
                    //             ]);
                    //             $success['shipping'] = new shippingResource($shipping);
                    //         } else {

                    //             $ship_id = null;
                    //             $track_id = null;
                    //             $success['shippingCompany'] = $ship->msg->message;
                    //             return $this->sendResponse($success, "خطأ في البيانات المدخلة", "message");
                    //         }
                } else {
                            $order->update([
                                'order_status' => $request->input('status'),
                            ]);
                            foreach ($order->items as $orderItem) {
                                $orderItem->update([
                                    'order_status' => $request->input('status'),
                                ]);
                            }
                            $shipping = Shipping::create([
                                'shipping_id' => $order->order_number,
                                'track_id' => null,
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
                            $success['shipping'] = new shippingResource($shipping);
                }
                $success['orders'] = new OrderResource($order);
                $success['status'] = 200;

                return $this->sendResponse($success, 'تم التعديل بنجاح', 'Order updated successfully');
            }
            if ($request->status === "refund") {
                $orderAddress = OrderOrderAddress::where('order_id', $order->id)->where('type', 'shipping')->value('order_address_id');
                $address = OrderAddress::where('id', $orderAddress)->first();
                $shipping = Shipping::where('order_id', $order->id)->first();
                 
                $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
                if($shipping == null){
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
                                        "Line1": "' .$address->street_address. '",
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
                                        "PersonName": "' . $order->user->name. '",
                                        "Title": "",
                                        "CompanyName": "' . $order->user->name . '",
                                        "PhoneNumber1": "' . $order->user->phonenumber . '",
                                        "PhoneNumber1Ext": "",
                                        "PhoneNumber2": "",
                                        "PhoneNumber2Ext": "",
                                        "FaxNumber": "",
                                        "CellPhone": "' . $order->user->phonenumber . '",
                                        "EmailAddress": "' . $order->user->email  . '",
                                        "Type": ""
                                    }
                                },
                                "Consignee": {
                                    "Reference1": "",
                                    "Reference2": "",
                                    "AccountNumber": "",
                                    "PartyAddress": {
                                        "Line1": "' .$shipping->streetaddress . '",
                                        "Line2": "' . $shipping->streetaddress . '",
                                        "Line3": "",
                                        "City": "' . $shipping ->city . '",
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

                    $ar = new AramexService();

                    $arData = $ar->createOrder($json);
                    if ($arData->HasErrors == false) {
                        $success['shippingCompany'] = $arData;
                    }

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
                            $total_price_without_shipping = ($order->total_price)-($shipping_price)-($extra_shipping_price);
                            $data = [

                                "Key" => $payment->paymentTransectionID,
                                "KeyType" => "invoiceid",
                                "RefundChargeOnCustomer" => false,
                                "ServiceChargeOnCustomer" => false,
                                "Amount" =>  $total_price_without_shipping,
                                "Comment" => "refund to the customer",
                                "AmountDeductedFromSupplier" => 0,
                                "CurrencyIso" => "SA",
                            ];
                            
                         
                            $supplier = new FatoorahServices();
                            $supplierCode = $supplier->createSupplier('v2/MakeRefund', $data);
                        
                            if ($supplierCode->IsSuccess == false) {
                                return $this->sendError("خطأ في الارجاع", $supplierCode->ValidationErrors[0]->Error);
                            }
                            else{
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


    public function deleteall(Request $request)
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
}
