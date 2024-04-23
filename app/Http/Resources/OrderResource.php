<?php

namespace App\Http\Resources;

use App\Models\Store;
use App\Models\User;
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
            $status =  __('message.new');
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
     


        if ($this->payment_status == null || $this->payment_status == 'pending') {
            $paymentstatus = __('message.paymentpending');
        }
        elseif ($this->payment_status == 'paid') {
            $paymentstatus = __('message.paid');
        } elseif ($this->payment_status == 'failed') {
            $paymentstatus= __('message.failed');
        }
        if ($this->shippingtype == null) {
            $track = null;
        } elseif ($this->shippingtype->id == 1) {
            $track = 'https://www.aramex.com/sa/ar/home#';
        } elseif ($this->shippingtype->id == 2) {
            $track = 'https://sdm.smsaexpress.com/';
        } elseif ($this->shippingtype->id == 3) {
            $track = 'https://www.imile.com/ar/track/';
        } elseif ($this->shippingtype->id == 4) {
            $track = 'https://www.jtexpress-sa.com/trajectoryQuery';
        } else {
            $track = null;
        }
        // $subtotal = $this->subtotal - $this->tax;
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'admin_employee') {
            $storeAdmain = User::whereIn('user_type', ['store', 'store_employee'])->where('id', $this->user->id)->first();
            if ($storeAdmain != null) {
                $store = Store::where('id', $storeAdmain->store_id)->first();
            } else {
                $store = null;
            }

        } else {
            $store = null;
        }
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
            'subtotal' => round($this->subtotal, 2),
            'total_price' => round($this->total_price, 2),
            'discount' => $this->discount != null ?-($this->discount) : 0,
            'status' => $status,
            'payment_status' => $paymentstatus,
            'created_at' => $this->created_at,
            'orderItem' => OrderItemsResource::collection($this->items),
            // 'OrderAddress' => $this->shippingAddress != null ? new OrderAddressResource($this->shippingAddress) : null,

            'OrderAddress' => $orderAddress != null ? new OrderAddressResource(\App\Models\OrderAddress::where('id', $orderAddress)->first()) : null,
            //   'billingAddress' => $billingAddress != null ? new OrderAddressResource(\App\Models\OrderAddress::where('id', $billingAddress)->first()):null,
            'shipping' => $this->shipping != null ? new shippingResource($this->shipping) : null,
            'paymenttypes' => $this->payment != null ? new PaymentResource($this->paymentype) : null,
            'shippingtypes' => $this->shippingtype != null ? new ShippingtypeResource($this->shippingtype) : null,
            'trackingLink' => $track,
            'cod' => $this->cod,
            'codprice' => $this->cod == 1 ? 10 : 0,
            'description' => $this->description,
            'is_deleted' => $this->is_deleted,
            'store' => $store,
        ];
    }
}
