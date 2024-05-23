<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\OrderItemsResource;
use App\Http\Resources\PaymenttypeResource;
use App\Http\Resources\ShippingtypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentOrderResource extends JsonResource
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
        if ($this->payment_status == null || $this->payment_status == 'pending') {
            $paymentstatus = __('message.paymentpending');
        } elseif ($this->payment_status == 'paid') {
            $paymentstatus = __('message.paid');
        } elseif ($this->payment_status == 'failed') {
            $paymentstatus = __('message.failed');
        }

        if ($this->order_status == null || $this->order_status == 'new') {
            $status = __('message.new');
        } elseif ($this->order_status == 'completed') {
            $status = __('message.completed');
        } elseif ($this->order_status == 'not_completed') {
            $status = __('message.not_completed');
        } elseif ($this->order_status == 'delivery_in_progress') {
            $status = __('message.delivery_in_progress');
        } elseif ($this->order_status == 'ready') {
            $status = __('message.ready');
        } elseif ($this->order_status == 'canceled') {
            $status = __('message.canceled');
        }
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'user' => new UserResource($this->user),
            'totalCount' => $this->totalCount,
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'overweight' => $this->weight > 15 ? ($this->weight - 15) : 0,
            'overweight_price' => $this->weight > 15 ? round(($this->weight - 15) * 3, 2) : 0,
            'tax' => round($this->tax, 2),
            'shipping_price' => $this->shipping_price,
            'subtotal' => round($this->subtotal, 2),
            'total_price' => round($this->total_price, 2),
            'discount' => $this->discount != null ? -($this->discount) : 0,
            'status' => $status,
            'OrderAddress' => $orderAddress != null ? new OrderAddressResource(\App\Models\OrderAddress::where('id', $orderAddress)->first()) : null,
            'orderItem' => OrderItemsResource::collection($this->items),
            'payment_status' => $paymentstatus,
            'shippingtypes' => $this->shippingtype != null ? new ShippingtypeResource($this->shippingtype) : null,
            'paymenttype' => $this->paymentype != null ? new PaymenttypeResource($this->paymentype) : null,      
            'shipping' => $this->shippings->where('shipping_type', 'send')->first() != null ? new shippingResource($this->shippings->where('shipping_type', 'send')->first()) : null,
            'shipping_return' => $this->shippings->where('shipping_type', 'return')->first() != null ? new shippingResource($this->shippings->where('shipping_type', 'return')->first()) : null,
            'is_return' => $this->is_return,
            'created_at' => $this->created_at,
        ];
    }
}
