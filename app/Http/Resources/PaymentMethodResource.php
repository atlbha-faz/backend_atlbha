<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if ($this->status == null || $this->status == 'active') {
            $status = __('message.active');
        } else {
            $status = __('message.not_active');
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'paymentMethodId' => $this->paymentMethodId,
            'description' => $this->description,
            'codprice' => $this->id == 4 ? 10 : 0,
            'status' => $status,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,

        ];
    }
}
