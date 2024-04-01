<?php

namespace App\Http\Resources;

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
        if ($this->payment_status == null || $this->payment_status == 'pending') {
            $paymentstatus =  __('message.paymentpending');
        }
        elseif ($this->payment_status == 'paid') {
            $paymentstatus =  __('message.paid');
        } elseif ($this->payment_status == 'failed') {
            $paymentstatus= __('message.failed');
        }

        if ($this->order_status == null || $this->order_status == 'new') {
            $status =  __('message.new');
        } elseif ($this->order_status == 'completed') {
            $status = __('message.completed');
        } elseif ($this->order_status == 'not_completed') {
            $status = __('message.not_completed');
        } elseif ($this->order_status == 'delivery_in_progress') {
            $status =  __('message.delivery_in_progress');
        } elseif ($this->order_status == 'ready') {
            $status =  __('message.ready');
        } elseif ($this->order_status == 'canceled') {
            $status = __('message.canceled');
        }
        elseif ($this->order_status == 'refund') {
            $status =  __('message.refund');
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
        'discount' => $this->discount != null ?-($this->discount) : 0,
        'status' => $status,
        'payment_status' => $paymentstatus,
        'created_at' => $this->created_at,
        ];
    }
}
