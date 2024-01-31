<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'bankId'=>$this->bankId,
            'bankAccountHolderName'=>$this->bankAccountHolderName,
            'bankAccount'=>$this->bankAccount,
            'iban'=>$this->iban,
            'supplierCode'=>$this->supplierCode,
        ];
    }
}
