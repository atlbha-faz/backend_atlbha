<?php

namespace App\Http\Resources;

use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymenttypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $is_madfu = false;
        if (auth()->user()->user_type == 'store' || auth()->user()->user_type == 'store_employee') {
            if ($this->stores()->where('store_id', auth()->user()->store_id)->first() != null) {
                $status = __('message.active');
            } else {
                $status = __('message.not_active');
            }
        } else {
            if ($this->status == null || $this->status == 'active') {
                $status = __('message.active');
            } else {
                $status = __('message.not_active');
            }
        }
        $store = Store::find(auth()->user()->store_id);
        $is_madfu = ($store) ? ($store->madfu_username != null && $store->madfu_password != null && $this->id == 5) : false;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'paymentMethodId' => $this->paymentMethodId,
            'description' => $this->description,
            'codprice' => $this->id == 4 ? 10 : 0,
            'status' => $status,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'is_madfu' => $is_madfu,

        ];
    }
}
