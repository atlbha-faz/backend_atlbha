<?php
namespace App\Services\ShippingComanies;

use App\Http\Resources\OrderResource;
use App\Interfaces\ShippingInterface;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderOrderAddress;
use App\Models\Shipping;
use App\Models\Store;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class AramexCompanyService implements ShippingInterface
{
    private $base_url;
    private $headers;
    public function __construct()
    {

        $this->base_url = env('aramex_base_url', 'https://ws.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments');
        $this->headers = [
            "Content-Type" => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function buildRequest($mothod, $data)
    {

        $client = new Client();

        $request = new Request($mothod, $this->base_url, $this->headers, $data);
        $response = $client->sendAsync($request)->wait();
        if ($response->getStatusCode() != 200) {
            return false;
        }
        $response = json_decode((string) $response->getBody());
        return $response;
    }
    public function tracking($id)
    {

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $body = '{
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
          "GetLastTrackingUpdateOnly": false,
          "Shipments": [
            "' . $id . '"
          ],
          "Transaction": {
            "Reference1": "",
            "Reference2": "",
            "Reference3": "",
            "Reference4": "",
            "Reference5": ""
          }
        }';
        $request = new Request('POST', 'https://ws.aramex.net/ShippingAPI.V2/Tracking/Service_1_0.svc/json/TrackShipments', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        $response = json_decode($res->getBody());
        return $response;

    }
    public function createOrder($data)
    {
        $order = Order::where('id', $data["order_id"])->first();
        $orderAddress = OrderOrderAddress::where('order_id', $data["order_id"])->where('type', 'shipping')->value('order_address_id');
        $address = OrderAddress::where('id', $orderAddress)->first();
        $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
        //  if( $order->payment_status=='paid'|| $order->cod ==1)
        //  {

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
                    "Reference1": "' . $data["order_id"] . '",
                    "Reference2": "",
                    "Reference3": "",
                    "Shipper": {
                        "Reference1": "",
                        "Reference2": "",
                        "AccountNumber": "117620",
                        "PartyAddress": {
                            "Line1": "' . $data["shipper_line1"] . '",
                            "Line2": "' . $data["shipper_line2"] . '",
                            "Line3": "",
                            "City": "' . $data["shipper_city"] . '",
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
                            "PersonName": "' . $data["shipper_name"] . '",
                            "Title": "",
                            "CompanyName": "' . $data["shipper_comany"] . '",
                            "PhoneNumber1": "' . $data["shipper_phonenumber"] . '",
                            "PhoneNumber1Ext": "",
                            "PhoneNumber2": "",
                            "PhoneNumber2Ext": "",
                            "FaxNumber": "",
                            "CellPhone": "' . $data["shipper_phonenumber"] . '",
                            "EmailAddress": "' . $data["shipper_email"] . '",
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
                        "AccountNumber": "117620",
                        "PartyAddress": {
                            "Line1": "الرويس",
                            "Line2": "طريق المدينة المنورة",
                            "Line3": "",
                            "City": "jeddah",
                            "StateOrProvinceCode": "Western Province",
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
                            "PersonName": "FAZ",
                            "Title": "",
                            "CompanyName": "FAZ",
                            "PhoneNumber1": "+966506340450",
                            "PhoneNumber1Ext": "",
                            "PhoneNumber2": "",
                            "PhoneNumber2Ext": "",
                            "FaxNumber": "",
                            "CellPhone": "+966506340450",
                            "EmailAddress": "info@atlbha.sa",
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
                        "PaymentType": "3",
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

        $arData = $this->buildRequest('POST', $json);
        if ($arData->HasErrors == true) {
            $errorsMessages = "";

            foreach ($arData->Notifications as $error) {
                $errorsMessages .= $error->Message . ",";
                // array_push($errorsMessages, $error->Message);
            }
            foreach ($arData->Shipments as $key => $Shipment) {
                foreach ($Shipment->Notifications as $key => $error) {
                    $errorsMessages .= $error->Message;
                }
            }
            return $errorsMessages;
        } else {
            $ship_id = $arData->Shipments[0]->ID;
            $url = $arData->Shipments[0]->ShipmentLabel->LabelURL;
            $track_id=null;
            if ($ship_id) {
                $tracking= $this->tracking($ship_id);
                $track_id=$tracking->TrackingResults[0]->Key;
            }
            $order->update([
                'order_status' => "ready",
            ]);
            foreach ($order->items as $orderItem) {
                $orderItem->update([
                    'order_status' => "ready",
                ]);
            }
            $shipping = Shipping::create([

                'order_id' => $order->id,
                'store_id' => $order->store_id,
                'shipping_id' => $ship_id,
                'track_id'=>$track_id,
                'sticker' => $url,
                'description' => $order->description,
                'price' => $order->total_price,
                'district' => $data["shipper_district"],
                'city' => $data["shipper_city"],
                'streetaddress' => $data["shipper_line1"],
                'customer_id' => $order->user_id,
                'destination_district' => $address->district,
                'destination_city' => $address->city,
                'destination_streetaddress' => $address->street_address,
                'shipping_type' => 'send',

            ]);

            return new OrderResource($order);

        }
    }
    public function refundOrder($order_id)
    {
        $order = Order::where('id', $order_id)->first();
        // $orderAddress = OrderOrderAddress::where('order_id', $order_id)->where('type', 'shipping')->value('order_address_id');
        // $address = OrderAddress::where('id', $orderAddress)->first();
        $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
        $shipping = Shipping::where('order_id', $order_id)->first();
        $store = Store::where('id', $order->store_id)->first();
        if ($shipping == null) {
            return $this->sendError("لايمكن استرجاع الطلب", "shipping is't exists");
        }

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
                                        "Line1": "' . $shipping->destination_streetaddress . '",
                                        "Line2": "' . $shipping->destination_streetaddress . '",
                                        "Line3": "",
                                        "City": "' . $shipping->destination_city . '",
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
                                        "PersonName": "' . $store->user->name . '",
                                        "CompanyName": "' . $store->store_name . '",
                                        "PhoneNumber1": "' . $store->phonenumber . '",
                                        "PhoneNumber1Ext": "",
                                        "PhoneNumber2": "",
                                        "PhoneNumber2Ext": "",
                                        "FaxNumber": "",
                                        "CellPhone": "' . $store->phonenumber . '",
                                        "EmailAddress": "' . $store->store_email . '",
                                        "Type": ""
                                    }
                                },
                                "ThirdParty": {
                                    "Reference1": "",
                                    "Reference2": "",
                                    "AccountNumber": "117620",
                                    "PartyAddress": {
                                        "Line1": "الرويس",
                                        "Line2": "طريق المدينة المنورة",
                                        "Line3": "",
                                        "City": "jeddah",
                                        "StateOrProvinceCode": "Western Province",
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
                                        "PersonName": "FAZ",
                                        "Title": "",
                                        "CompanyName": "FAZ",
                                        "PhoneNumber1": "+966506340450",
                                        "PhoneNumber1Ext": "",
                                        "PhoneNumber2": "",
                                        "PhoneNumber2Ext": "",
                                        "FaxNumber": "",
                                        "CellPhone": "+966506340450",
                                        "EmailAddress": "info@atlbha.sa",
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
                                    "PaymentType": "3",
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

        $arData = $this->buildRequest('POST', $json);
        if ($arData->HasErrors == true) {
            $errorsMessages = "";

            foreach ($arData->Notifications as $error) {
                $errorsMessages .= $error->Message . ",";
                // array_push($errorsMessages, $error->Message);
            }
            foreach ($arData->Shipments as $key => $Shipment) {
                foreach ($Shipment->Notifications as $key => $error) {
                    $errorsMessages .= $error->Message;
                }
            }
            return $errorsMessages;
        } else {
            $ship_id = $arData->Shipments[0]->ID;
            $url = $arData->Shipments[0]->ShipmentLabel->LabelURL;

            $shipping = Shipping::Create([
                'order_id' => $order->id,
                'store_id' => $order->store_id,
                'shipping_id' => $ship_id,
                'sticker' => $url,
                'description' => $order->description,
                'price' => $order->total_price,
                'city' => $shipping->destination_city,
                'streetaddress' => $shipping->destination_streetaddress,
                'customer_id' => $order->user_id,
                'destination_district' => $shipping->district,
                'destination_city' => $shipping->city,
                'destination_streetaddress' => $shipping->streetaddress,
                'shipping_type' => 'return',
            ]);

            return new OrderResource($order);
        }

    }
    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)->first();
        if ($order->order_status == "new" || $order->order_status == "ready") {

            if ($order->paymentype_id == 1 && $order->payment_status == "paid") {
                $payment = Payment::where('orderID', $order->id)->first();

                $data = [
                    "Key" => $payment->paymentTransectionID,
                    "KeyType" => "invoiceid",
                    "RefundChargeOnCustomer" => false,
                    "ServiceChargeOnCustomer" => false,
                    "Amount" => $order->total_price,
                    "Comment" => "refund to the customer",
                    "AmountDeductedFromSupplier" => $payment->price_after_deduction,
                    "CurrencyIso" => "SAR",
                ];

                $supplier = new FatoorahServices();

                $response = $supplier->refund('v2/MakeRefund', 'POST', $data);
                if ($response) {
                    if ($response['IsSuccess'] == false) {
                        return $this->sendError("خطأ في الارجاع", $supplierCode->ValidationErrors[0]->Error);
                    } else {
                        $success['message'] = $response;
                    }
                } else {
                    return $this->sendError("خطأ في الارجاع المالي", 'error');
                }
            }
        }
        $order->update([
            'order_status' => 'canceled',
        ]);
        foreach ($order->items as $orderItem) {
            $orderItem->update([
                'order_status' => 'canceled',
            ]);
            if ($order->payment_status == "paid") {
                $product = \App\Models\Product::where('id', $orderItem->product_id)->where('store_id', $orderItem->store_id)->first();
                if ($product) {
                    $product->stock = $product->stock + $orderItem->quantity;
                    $product->save();
                } else {
                    $import_product = \App\Models\Importproduct::where('product_id', $orderItem->product_id)->where('store_id', $orderItem->store_id)->first();
                    if ($import_product) {
                        $import_product->qty = $import_product->qty + $orderItem->quantity;
                        $import_product->save();
                    }

                }
                // $order->is_archive = 1;
                // $order->save();
            }
        }

        return new OrderResource($order);

    }

}
