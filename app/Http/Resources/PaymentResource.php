<?php

namespace App\Http\Resources;

use App\Http\Resources\PaymentOrderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        
        return [
            'id' =>$this->id,
            'paymenDate' => $this->paymenDate,
            'paymentType' => $this->paymentType,
            'paymentTransectionID' => $this->paymentTransectionID,
            // 'paymentCardID' => $this->paymentCardID,
            'order' =>new PaymentOrderResource( $this->order),
            'deduction' => $this->deduction,
            'price_after_deduction' => $this->price_after_deduction,
            'created_at' => (string) $this->created_at,
             'updated_at' => (string) $this->updated_at,
          ];
    }
}
?>