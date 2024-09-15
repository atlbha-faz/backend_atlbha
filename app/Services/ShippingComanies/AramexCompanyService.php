<?php
namespace App\Services\ShippingComanies;

use App\Http\Resources\OrderResource;
use App\Interfaces\ShippingInterface;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderOrderAddress;
use App\Models\Payment;
use App\Models\ReturnOrder;
use App\Models\Shipping;
use App\Models\Store;
use App\Services\FatoorahServices;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use DateTime;
class AramexCompanyService implements ShippingInterface
{
    private $base_url;
    private $headers;
    public function __construct()
    {

        $this->base_url = env('aramex_base_url', 'https://ws.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/');
        $this->headers = [
            "Content-Type" => 'application/json',
            'Accept' => 'application/json',
        ];
    }
    public function sendError($error, $error_en, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => ['en' => $error_en, 'ar' => $error],

        ];

        if (!empty($errorMessages)) {
            # code...
            $response['data'] = $errorMessages;
        } else {
            $response['data'] = null;
        }

        return response()->json($response, $code);

    }
    public function buildRequest($mothod, $data, $url)
    {

        $client = new Client();

        $request = new Request($mothod, $this->base_url . $url, $this->headers, $data);
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
        "UserName": "info@atlbha.sa",
        "Password": "Rm20$ljl24F",
        "Version": "1.0",
        "AccountNumber": "72037322",
        "AccountPin": "466605",
        "AccountEntity": "JED",
        "AccountCountryCode": "SA",
        "Source": 25,
        "PreferredLanguageCode": null
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
        $cashOnDeleviry = $order->paymentype_id == 4 ? $order->total_price : 0 ;
        $service = $order->paymentype_id == 4 ? "CODS" : "";
        $store = Store::where('is_deleted', 0)->where('id', $order->store_id)->first();
        $address = OrderAddress::where('id', $orderAddress)->first();
        $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
        //  if( $order->payment_status=='paid'|| $order->cod ==1)
        //  {

        $json = '{
            "ClientInfo": {
                "UserName": "info@atlbha.sa",
                "Password": "Rm20$ljl24F",
                "Version": "v1",
                "AccountNumber": "72037322",
                "AccountPin": "466605",
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
                        "AccountNumber": "",
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
                        "AccountNumber": "72037322",
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
                    "DueDate": "\\/Date(' . $data["pickup_date"] . ')\\/",
                    "Comments": "",
                    "PickupLocation": "",
                    "OperationsInstructions": "",
                    "AccountingInstrcutions": "",
                    "Details": {
                        "Dimensions": null,
                        "ActualWeight": {
                            "Unit": "KG",
                            "Value":' . $order->weight . '
                        },
                        "ChargeableWeight": null,
                        "DescriptionOfGoods": "' . $store->categories->first()->name . '",
                        "GoodsOriginCountry": "SA",
                        "NumberOfPieces": ' . $order->totalCount . ',
                        "ProductGroup": "DOM",
                        "ProductType": "cds",
                        "PaymentType": "3",
                        "PaymentOptions": "",
                        "CustomsValueAmount": null,
                        "CashOnDeliveryAmount": {
                            "CurrencyCode": "SAR",
                            "Value":' . $cashOnDeleviry. '
                        },
                        "InsuranceAmount": null,
                        "CashAdditionalAmount": null,
                        "CashAdditionalAmountDescription": "",
                        "CollectAmount": null,
                        "Services": ' .$service. ',
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

        $arData = $this->buildRequest('POST', $json, "CreateShipments");
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
            return response()->json(['error' => $errorsMessages], 404);
        } else {
            $ship_id = $arData->Shipments[0]->ID;
            $url = $arData->Shipments[0]->ShipmentLabel->LabelURL;

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
                'track_id' => $ship_id,
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
                'pickup_date'=> $data["pickup_date"],
                'shipping_type' => 'send',

            ]);
            $response = [
                'success' => true,
                'data' => ['orders' => new OrderResource($order), 'status' => 200],
                'message' => ['ar' => 'تم تعديل الطلب', 'en' => "Order updated successfully"],
            ];
            return response()->json($response, 200);
        }
    }
    public function createPickup($data)
    {
        $order = Order::where('id', $data["order_id"])->first();
        $orderAddress = OrderOrderAddress::where('order_id', $data["order_id"])->where('type', 'shipping')->value('order_address_id');
        $cashOnDeleviry = $order->paymentype_id == 4 ? 0 : $order->total_price;
        $store = Store::where('is_deleted', 0)->where('id', $order->store_id)->first();
        $address = OrderAddress::where('id', $orderAddress)->first();
        $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
        //  if( $order->payment_status=='paid'|| $order->cod ==1)
        //  {

        $json = '{
            "ClientInfo": {
                "UserName": "info@atlbha.sa",
                "Password": "Rm20$ljl24F",
                "Version": "v1",
                "AccountNumber": "72037322",
                "AccountPin": "466605",
                "AccountEntity": "JED",
                "AccountCountryCode": "SA",
                "Source": 24
            },
            "LabelInfo": {
                "ReportID": 9729,
                "ReportType": "URL"
            },
           "Pickup": {
            "PickupAddress": {
            "Line1": "' . $data["shipper_line1"] . '",
            "Line2": "' . $data["shipper_line2"] . '",
            "Line3": "",
            "City": "' . $data["shipper_city"] . '",
            "StateOrProvinceCode": "",
            "PostCode": "",
            "CountryCode": "sa",
            "Longitude": 0,
            "Latitude": 0,
            "BuildingNumber": null,
            "BuildingName": null,
            "Floor": null,
            "Apartment": null,
            "POBox": null,
            "Description": null
                },
            "PickupContact": {
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
            },
            "PickupLocation": "' . $data["shipper_line2"] . '",
           "PickupDate":"\\/Date(' . $data["pickup_date"] . ')\\/",
           "ReadyTime": "\/Date(' . $data["pickup_date"] . ')\/",
           "LastPickupTime": "\/Date(' . $data["pickup_date"] . ')\/",
           "ClosingTime": "\/Date(' . $data["pickup_date"] . ')\/",
            "Comments": "",
            "Reference1": "' . $data["order_id"] . '",
            "Reference2": "",
            "Vehicle": "",
            "Shipments": [],
             "PickupItems": [
              {
                "ProductGroup": "DOM",
                "ProductType": "CDS",
                "NumberOfShipments": 1,
                "PackageType": "Box",
                "Payment": "3",
                "ShipmentWeight": {
                    "Unit": "KG",
                    "Value": ' . $order->weight . '
                },
                "ShipmentVolume": null,
                "NumberOfPieces": ' . $order->totalCount . ',
                "CashAmount": null,
                "ExtraCharges": null,
                "ShipmentDimensions": {
                    "Length": 0,
                    "Width": 0,
                    "Height": 0,
                    "Unit": ""
                },
                "Comments": ""
              }
          ],
        "Status": "Ready",
        "ExistingShipments": null,
        "Branch": "",
        "RouteCode": ""
          },
            "Transaction": {
                "Reference1": "",
                "Reference2": "",
                "Reference3": "",
                "Reference4": "",
                "Reference5": ""
            }
        }';

        $arData = $this->buildRequest('POST', $json, "CreatePickup");
        if ($arData->HasErrors == true) {
            $errorsMessages = "";

            foreach ($arData->Notifications as $error) {
                $errorsMessages .= $error->Message . ",";
                // array_push($errorsMessages, $error->Message);
            }

            return response()->json(['error' => $errorsMessages], 404);
        } else {
            $pickup_id = $arData->ProcessedPickup->ID;

            $order->update([
                'order_status' => "delivery_in_progress",
            ]);
            foreach ($order->items as $orderItem) {
                $orderItem->update([
                    'order_status' => "delivery_in_progress",
                ]);
            }
            $shipping = Shipping::where('order_id', $order->id)->where('shipping_type', 'send')->first();
            $shipping->update([
                'track_id' => $pickup_id,
                'pickup_date'=> $data["pickup_date"],
            ]);

            $response = [
                'success' => true,
                'data' => ['orders' => new OrderResource($order), 'status' => 200],
                'message' => ['ar' => 'تم تعديل الطلب', 'en' => "Order updated successfully"],
            ];
            return response()->json($response, 200);
        }
    }
    public function workDay($timestampMilliseconds)
    {
        $timestampSeconds = $timestampMilliseconds / 1000;

        // Create a Carbon instance from the timestamp
        $dateTime = Carbon::createFromTimestamp($timestampSeconds);

        // Set the timezone (adjust as needed)
        $dateTime->setTimezone('America/New_York'); // Change to your desired timezone

        // Get the timezone offset in the required format
        $timezoneOffset = $dateTime->format('P'); // ±HH:MM
        $timezoneOffsetFormatted = str_replace(':', '', $timezoneOffset); // Remove the colon

        // Format the output as required
        $formattedDate = sprintf('\/Date(%d%s)\/', $timestampMilliseconds, $timezoneOffsetFormatted);
        return $formattedDate;
    }
    public function createReversePickup($order_id)
    {
        $order = Order::where('id', $order_id)->first();
        // $orderAddress = OrderOrderAddress::where('order_id', $order_id)->where('type', 'shipping')->value('order_address_id');
        // $address = OrderAddress::where('id', $orderAddress)->first();
        // $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
        $shipping = Shipping::where('order_id', $order_id)->where('shipping_type', 'send')->first();
        //   dd($this->workDay($shippingDate));
        $dateTime = new DateTime();

        // Check if the day is Thursday (4 corresponds to Thursday)
        if ($dateTime->format('N') == 4) {
            $dateTime->modify('next Saturday');
        } else {
            $dateTime->modify('tomorrow');
        }
        $dateTime->setTime(9, 0);
        $carbonDateTime = Carbon::instance($dateTime);

        // Get the precise timestamp with 3 decimal places for milliseconds
        $preciseTimestamp = $carbonDateTime->getPreciseTimestamp(3);
        $shippingDate = $this->workDay($preciseTimestamp);

        $json = '{
          "ClientInfo": {
        "UserName": "info@atlbha.sa,
        "Password": "Rm20$ljl24F",
        "Version": "1.0",
        "AccountNumber": "72037322",
        "AccountPin": "466605",
        "AccountEntity": "JED",
        "AccountCountryCode": "SA",
        "Source": 25
        },
        "LabelInfo": {
            "ReportID": 9729,
            "ReportType": "URL"
        },
        "Pickup": {
            "PickupAddress": {
                "Line1": "' . $shipping->destination_streetaddress . '",
                "Line2": "' . $shipping->destination_streetaddress . '",
                "Line3": "",
                "City": "' . $shipping->destination_city . '",
                "StateOrProvinceCode": "",
                "PostCode": "",
                "CountryCode": "sa",
                "Longitude": 0,
                "Latitude": 0,
                "BuildingNumber": null,
                "BuildingName": null,
                "Floor": null,
                "Apartment": null,
                "POBox": null,
                "Description": null
            },
            "PickupContact": {
                "Department": "",
                "PersonName": "' . $order->user->name . '",
                "Title": "",
                "CompanyName": "' . $order->user->name . '",
                "PhoneNumber1": "' . $order->user->phonenumber . '",
                "PhoneNumber1Ext": "",
                "PhoneNumber2": "",
                "PhoneNumber2Ext": "",
                "FaxNumber": "",
                "CellPhone":  "' . $order->user->phonenumber . '",
                "EmailAddress": "' . $order->user->email . '",
                "Type": ""
            },
            "PickupLocation": "test",
            "PickupDate": "' . $shippingDate . '",
            "ReadyTime": "' . $shippingDate . '",
            "LastPickupTime": "' . $shippingDate . '",
            "ClosingTime": "' . $shippingDate . '",
            "Comments": "",
            "Reference1": "' . $order->id . '",
            "Reference2": "",
            "Vehicle": "",
            "Shipments": [],
            "PickupItems": [
                {
                    "ProductGroup": "DOM",
                    "ProductType": "Rtn",
                    "NumberOfShipments": 1,
                    "PackageType": "Box",
                    "Payment": "3",
                    "ShipmentWeight": {
                        "Unit": "KG",
                        "Value": ' . $order->weight . '
                    },
                    "ShipmentVolume": null,
                    "NumberOfPieces": ' . $order->totalCount . ',
                    "CashAmount": null,
                    "ExtraCharges": null,
                    "ShipmentDimensions": {
                        "Length": 0,
                        "Width": 0,
                        "Height": 0,
                        "Unit": ""
                    },
                    "Comments": ""
                }
            ],
            "Status": "Ready",
            "ExistingShipments": null,
            "Branch": "",
            "RouteCode": ""
        },
        "Transaction": {
            "Reference1": "",
            "Reference2": "",
            "Reference3": "",
            "Reference4": "",
            "Reference5": ""
        }
    }';

        $arData = $this->buildRequest('POST', $json, "CreatePickup");
        if ($arData->HasErrors == true) {
            $errorsMessages = "";

            foreach ($arData->Notifications as $error) {
                $errorsMessages .= $error->Message . ",";
                // array_push($errorsMessages, $error->Message);
            }

            return response()->json(['error' => $errorsMessages], 404);
        } else {
            $pickup_id = $arData->ProcessedPickup->ID;

            // $order->update([
            //     'order_status' => "ready",
            // ]);
            // foreach ($order->items as $orderItem) {
            //     $orderItem->update([
            //         'order_status' => "ready",
            //     ]);
            // }
            $shipping = Shipping::where('order_id', $order->id)->where('shipping_type', 'return')->first();
            $shipping->update([
                'track_id' => $pickup_id,
                'pickup_date'=> $shippingDate,
            ]);

            $response = [
                'success' => true,
                'data' => ['orders' => new OrderResource($order), 'status' => 200],
                'message' => ['en' => 'تم تعديل الطلب', 'ar' => "Order updated successfully"],
            ];
            return response()->json($response, 200);

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
                                 "UserName": "info@atlbha.sa",
                                 "Password": "Rm20$ljl24F",
                                 "Version": "v1",
                                 "AccountNumber": "72037322",
                                 "AccountPin": "466605",
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
                                        "AccountNumber": "",
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
                                            "PersonName": "' . ($store->user->name == null ? $store->user->user_name : $store->user->name) . '",
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
                                        "AccountNumber": "72037322",
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
                                    "DueDate":  "\\/Date(' . $shippingDate . ')\\/",
                                    "Comments": "",
                                    "PickupLocation": "",
                                    "OperationsInstructions": "",
                                    "AccountingInstrcutions": "",
                                    "Details": {
                                        "Dimensions": null,
                                        "ActualWeight": {
                                            "Unit": "KG",
                                            "Value":  ' . $order->weight . '
                                        },
                                        "ChargeableWeight": null,
                                        "DescriptionOfGoods": "",
                                        "GoodsOriginCountry": "SA",
                                        "NumberOfPieces": ' . $order->totalCount . ',
                                        "ProductGroup": "DOM",
                                        "ProductType": "Rtn",
                                        "PaymentType": "3",
                                        "PaymentOptions": "",
                                        "CustomsValueAmount": null,
                                        "CashOnDeliveryAmount": {
                                            "CurrencyCode": "SAR",
                                            "Value": 0
                                        },
                                        "InsuranceAmount": null,
                                        "CashAdditionalAmount": null,
                                        "CashAdditionalAmountDescription": "",
                                        "CollectAmount": null,
                                        "Services": "",
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

        $arData = $this->buildRequest('POST', $json, "CreateShipments");
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
            return response()->json(['error' => $errorsMessages], 404);
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
                'pickup_date'=> $shippingDate,
                'shipping_type' => 'return',
            ]);

            $response = [
                'success' => true,
                'data' => ['orders' => new OrderResource($order), 'status' => 200],
                'message' => ['ar' => 'تم تعديل الطلب', 'en' => "Order updated successfully"],
            ];
            return response()->json($response, 200);
        }

    }
    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)->first();
        if ($order->order_status == "new" || $order->order_status == "ready") {

            $shippings = Shipping::where('order_id', $order_id)->get();
            if ($shippings != null) {
                foreach ($shippings as $shipping) {
                    $shipping->delete();
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
    public function refundCancelOrder($id)
    {
        $order = Order::where('id', $id)->first();
        if ($order->paymentype_id == 1 && $order->payment_status == "paid") {
            $payment = Payment::where('orderID', $order->id)->first();
            if ($order->store_id == null) {
                $data = [
                    "Key" => $payment->paymentTransectionID,
                    "KeyType" => "invoiceid",
                    "RefundChargeOnCustomer" => false,
                    "ServiceChargeOnCustomer" => false,
                    "Amount" => $payment->price_after_deduction,
                    "Comment" => "refund to the customer",
                    "AmountDeductedFromSupplier" => 0,
                    "CurrencyIso" => "SAR",
                ];
            } else {
                $data = [
                    "Key" => $payment->paymentTransectionID,
                    "KeyType" => "invoiceid",
                    "RefundChargeOnCustomer" => false,
                    "ServiceChargeOnCustomer" => false,
                    "Amount" => $payment->price_after_deduction,
                    "Comment" => "refund to the customer",
                    "AmountDeductedFromSupplier" => $payment->price_after_deduction,
                    "CurrencyIso" => "SAR",
                ];

            }
            $supplier = new FatoorahServices();

            $response = $supplier->refund('v2/MakeRefund', 'POST', $data);
            if ($response) {
                if ($response['IsSuccess'] == false) {
                    // return $this->sendError("خطأ في الارجاع", $supplierCode->ValidationErrors[0]->Error);
                    $success['error'] = "خطأ في الارجاع المالي";

                } else {
                    $success['message'] = $response;
                    $returns = ReturnOrder::where('order_id', $order->id)->get();
                    foreach ($returns as $return) {
                        $return->update([
                            'refund_status' => 1,
                        ]);
                    }
                }
            } else {
                $success['error'] = "خطأ في الارجاع المالي";
                // return $this->sendError("خطأ في الارجاع المالي", 'error');
            }
        }
    }

}
