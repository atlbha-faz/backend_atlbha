<?php
namespace App\Services;



use App\ModelTax;
use GuzzleHttp\Client;

use GuzzleHttp\Psr7\Request;

class AramexService
{
  private $base_url;
  private $token;
  private $headers;

  public function __construct()
  {

      $this->base_url = env('aramex_base_url', 'https://ws.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments');
      $this->headers = [
        "Content-Type" => 'application/json',
    ];
  }

  public function buildRequest($mothod, $data ){

    $client = new Client(); 
      $request = new Request($mothod , $this->base_url,$headers,$data);
      $response = $client->sendAsync($request)->wait();
      if ($response->getStatusCode() != 200)
          return false;
      $response = json_decode ($response->getBody (),true);
      return $response;
  }
  public function createOrder($mothod, $data ){
    $orderAddress = OrderOrderAddress::where('order_id', $order->id)->where('type', 'shipping')->value('order_address_id');
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

  
        $arData = $this->buildRequest('POST',$json);
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
  }
 
}
