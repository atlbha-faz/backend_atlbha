<?php

namespace App\Http\Resources;

use App\Http\Resources\CartDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $user = \App\Models\User::where('user_name',$this->identifier)->first();
        // $subtotal = $this->subtotal - $this->tax;
        return [

            'id' => $this->id,
            'count' => $this->count,
            'totalCount' => $this->totalCount,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value !== null ? $this->discount_value : 0,
            'discount_total' => $this->discount_total !== null ? -($this->discount_total) : 0,
            'free_shipping' => $this->free_shipping,
            'discount_expire_date' => $this->discount_expire_date,
            'message' => $this->message,
            'shipping_price' => $this->shipping_price,
            'tax' => round($this->tax, 2),
            'weight' => round($this->weight,2),
            'subtotal' => round($this->subtotal, 2),
            'overweight' => $this->weight > 15 ? ($this->weight - 15) : 0,
            'overweight_price' => $this->weight > 15 ? round(($this->weight - 15) * 3, 2) : 0,
            'total' => $this->total !== null ? round($this->total, 2) : 0,
            'user' => new UserResource($this->user),
            // 'store' => New StoreResource($this->store),
            'cartDetail' => $this->cartDetails !== null ? CartDetailResource::collection($this->cartDetails) : 0,
            'status' => "غير مكتمل",
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];

    }
}
