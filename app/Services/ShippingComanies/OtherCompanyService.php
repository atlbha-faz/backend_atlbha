<?php
namespace App\Services\ShippingComanies;

use Carbon\Carbon;
use App\Models\Order;
use GuzzleHttp\Client;
use App\Models\Shipping;
use App\Models\Payment;
use App\Models\ReturnOrder;
use App\Models\OrderAddress;
use GuzzleHttp\Psr7\Request;
use App\Models\OrderOrderAddress;
use App\Services\FatoorahServices;
use App\Http\Resources\OrderResource;
use App\Interfaces\ShippingInterface;

class OtherCompanyService implements ShippingInterface
{
    private $base_url;
    private $headers;
    public function __construct()
    {

    }

    public function buildRequest($mothod, $data,$url)
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
    public function createOrder($data)
    {
        $order = Order::where('id', $data["order_id"])->first();
        $orderAddress = OrderOrderAddress::where('order_id', $data["order_id"])->where('type', 'shipping')->value('order_address_id');
        $address = OrderAddress::where('id', $orderAddress)->first();
        $order->update([
            'order_status' => 'ready',
        ]);
        foreach ($order->items as $orderItem) {
            $orderItem->update([
                'order_status' => 'ready',
            ]);
        }
        $shipping = Shipping::Create(
            [
                'order_id' => $order->id,
                'store_id' => $order->store_id,

                'shipping_id' => $order->order_number,
                // 'track_id' => $track_id,
                // 'sticker' => $url,
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

        return new OrderResource($order);

    }
    public function createPickup($data)
    {
        $order = Order::where('id', $data["order_id"])->first();
        $order->update([
            'order_status' => 'delivery_in_progress',
        ]);
        foreach ($order->items as $orderItem) {
            $orderItem->update([
                'order_status' => 'delivery_in_progress',
                'pickup_date'=> $data["pickup_date"]
            ]);
        }
        return new OrderResource($order);
    }
    public function createReversePickup($data)
    {
        $order = Order::where('id', $data["order_id"])->first();
        return new OrderResource($order);
    }
    public function refundOrder($order_id)
    {
        $order = Order::where('id', $order_id)->first();

        // $orderAddress = OrderOrderAddress::where('order_id', $order_id)->where('type', 'shipping')->value('order_address_id');
        // $address = OrderAddress::where('id', $orderAddress)->first();
        $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
        $shipping = Shipping::where('order_id', $order_id)->first();
        if($shipping != null)
        {
        $shipping = Shipping::Create([
            'order_id' => $order->id,
            'store_id' => $order->store_id,
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
    }
        return new OrderResource($order);

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
    public function tracking($id)
    {

        return null;

    }
    public function refundCancelOrder($id){
        $order = Order::where('id', $id)->first();
        if ($order->paymentype_id == 1 && $order->payment_status == "paid") {
            $payment = Payment::where('orderID', $order->id)->first();
    
            $data = [
                "Key" => $payment->paymentTransectionID,
                "KeyType" => "invoiceid",
                "RefundChargeOnCustomer" => false,
                "ServiceChargeOnCustomer" => false,
                "Amount" =>$payment->price_after_deduction,
                "Comment" => "refund to the customer",
                "AmountDeductedFromSupplier" => $payment->price_after_deduction,
                "CurrencyIso" => "SAR",
            ];

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
                            'refund_status' => 1
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
