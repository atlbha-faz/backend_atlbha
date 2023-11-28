<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orderAddress = \App\Models\OrderOrderAddress::where('order_id', $this->id)->where('type', 'shipping')->value('order_address_id');
        $billingAddress = \App\Models\OrderOrderAddress::where('order_id', $this->id)->where('type', 'billing')->value('order_address_id');

        if ($this->order_status == null || $this->order_status == 'new') {
            $status = 'جديد';
        } elseif ($this->order_status == 'completed') {
            $status = 'مكتمل';
        } elseif ($this->order_status == 'not_completed') {
            $status = 'غير مكتمل';
        } elseif ($this->order_status == 'delivery_in_progress') {
            $status = 'جاهز للشحن';
        } elseif ($this->order_status == 'ready') {
            $status = 'جاهز للشحن';
        } elseif ($this->order_status == 'canceled') {
            $status = 'ملغي';
        }
        if ($this->shippingtype == null) {
            $track = null;
        } elseif ($this->shippingtype->id == 1) {
            $track = 'https://www.saee.sa/ar/track-your-shipment/';
        } elseif ($this->shippingtype->id == 2) {
            $track = 'https://sdm.smsaexpress.com/';
        } elseif ($this->shippingtype->id == 3) {
            $track = 'https://www.imile.com/ar/track/';
        } elseif ($this->shippingtype->id == 4) {
            $track = 'https://www.jtexpress-sa.com/trajectoryQuery';
        }
        $subtotal = $this->subtotal - $this->tax;

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'user' => new UserResource($this->user),
            // 'products' =>$this->products != null? ProductResource::collection($this->products):null,
            'totalCount' => $this->totalCount,
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'overweight' => $this->weight > 15 ? ($this->weight - 15) : 0,
            'overweight_price' => $this->weight > 15 ? round(($this->weight - 15) * 3, 2) : 0,
            'tax' => round($this->tax, 2),
            'shipping_price' => $this->shipping_price,
            'subtotal' => round($subtotal, 2),
            'total_price' => round($this->total_price, 2),
            'discount' => $this->discount != null ? $this->discount : 0,
            'status' => $status,
            'created_at' => $this->created_at,
            'orderItem' => OrderItemsResource::collection($this->items),
            // 'OrderAddress' => $this->shippingAddress != null ? new OrderAddressResource($this->shippingAddress) : null,

            'OrderAddress' => $orderAddress != null ? new OrderAddressResource(\App\Models\OrderAddress::where('id', $orderAddress)->first()) : null,
            //   'billingAddress' => $billingAddress != null ? new OrderAddressResource(\App\Models\OrderAddress::where('id', $billingAddress)->first()):null,

            'shipping' => $this->shipping != null ? new shippingResource($this->shipping) : null,

            'paymenttypes' => $this->paymentype != null ? new PaymentResource($this->paymentype) : null,
            'shippingtypes' => $this->shippingtype != null ? new ShippingtypeResource($this->shippingtype) : null,
            'trackingLink' => $track,
            'cod' => $this->cod,
            'codprice' => $this->cod = 1 ? 10 : 0,
            'description' => $this->description,
        ];
    }
}
