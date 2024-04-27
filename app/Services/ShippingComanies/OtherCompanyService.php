<?php
namespace App\Services\ShippingComanies;

use Carbon\Carbon;
use App\Models\Order;
use GuzzleHttp\Client;
use App\Models\Shipping;
use App\Models\OrderAddress;
use GuzzleHttp\Psr7\Request;
use App\Models\OrderOrderAddress;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use App\Interfaces\ShippingInterface;

class OtherCompanyService implements ShippingInterface{
    private $base_url;
    private $headers;
    public function __construct()
    {

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
    public function createOrder($data)
    {
        $order = Order::where('id', $data["order_id"])->first();
        $order->update([
            'order_status' =>'ready',
        ]);
        foreach ($order->items as $orderItem) {
            $orderItem->update([
                'order_status' =>'ready',
            ]);
        }
            $shipping = Shipping::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'store_id' => $order->store_id,
                ],
                [
                    'shipping_id' => $order->order_number,
                    // 'track_id' => $track_id,
                    // 'sticker' => $url,
                    'description' => $order->description,
                    'price' => $order->total_price,
                    'district' => $data["shipper_district"],
                    'city' => $data["shipper_city"],
                    'streetaddress' => $data["shipper_line1"],
                    'customer_id' => $order->user_id,

                ]);

            return new OrderResource($order);
               
    }
    public function refundOrder( $order_id)
    {
        $order = Order::where('id',  $order_id)->first();
      
        $orderAddress = OrderOrderAddress::where('order_id',  $order_id)->where('type', 'shipping')->value('order_address_id');
        $address = OrderAddress::where('id', $orderAddress)->first();
        $shippingDate = Carbon::parse(Carbon::now())->getPreciseTimestamp(3);
        $shipping = Shipping::where('order_id',  $order_id)->first();
            $shipping = Shipping::Create([
                    'order_id' => $order->id,
                    'store_id' => $order->store_id,
                    'description' => $order->description,
                    'price' => $order->total_price,
                    'city' => $address->city,
                    'streetaddress' =>  $address->street_address,
                    'customer_id' => $order->user_id,
                ]);

            return  new OrderResource($order);
        

    }
    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)->first();
        $order->update([
            'order_status' =>'canceled',
        ]);
        foreach ($order->items as $orderItem) {
            $orderItem->update([
                'order_status' =>'canceled',
            ]);
        }
        return new OrderResource($order);
           

    }

}