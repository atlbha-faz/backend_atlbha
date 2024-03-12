<?php
namespace App\Services;

class AramexService
{
    public function createOrder(array $data)
    {

        $newdata = json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ws.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
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
            "Reference1": "",
            "Reference2": "",
            "Reference3": "",
            "Shipper": {
                "Reference1": "",
                "Reference2": "",
                "AccountNumber": "117620",
                "PartyAddress": {
                    "Line1": "Test",
                    "Line2": "",
                    "Line3": "",
                    "City": "Gizan",
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
                    "PersonName": "Hosam Odeh Test Shipment",
                    "Title": "",
                    "CompanyName": "aramex",
                    "PhoneNumber1": "+966555555555",
                    "PhoneNumber1Ext": "",
                    "PhoneNumber2": "",
                    "PhoneNumber2Ext": "",
                    "FaxNumber": "",
                    "CellPhone": "966555555555",
                    "EmailAddress": "test@test.com",
                    "Type": ""
                }
            },
            "Consignee": {
                "Reference1": "",
                "Reference2": "",
                "AccountNumber": "",
                "PartyAddress": {
                    "Line1": "Consignee Address ",
                    "Line2": "",
                    "Line3": "",
                    "City": "jeddah",
                    "StateOrProvinceCode": "",
                    "PostCode": "",
                    "CountryCode": "SA",
                    "Longitude": 21.633417441658377,
                    "Latitude": 39.156273462357504,
                    "BuildingNumber": "",
                    "BuildingName": "",
                    "Floor": "",
                    "Apartment": "",
                    "POBox": null,
                    "Description": ""
                },
                "Contact": {
                    "Department": "",
                    "PersonName": "aramex",
                    "CompanyName": "aramex",
                    "PhoneNumber1": "966553244599",
                    "PhoneNumber1Ext": "",
                    "PhoneNumber2": "",
                    "PhoneNumber2Ext": "",
                    "FaxNumber": "",
                    "CellPhone": "966553244599",
                    "EmailAddress": "test@test.com",
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
            "ShippingDateTime": "\\/Date(1709199135000-0500)\\/",
            "DueDate": "\\/Date(1709199135000-0500)\\/",
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
                "DescriptionOfGoods": "Books",
                "GoodsOriginCountry": "JO",
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
}',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

    }
}
