<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == null || $this->status == 'active') {
            if($this->store_id == null && $this->comment_for == 'store'){
                  $status = 'تم النشر';
            }else{
            }
            $status = 'نشط';
        } else {
            $status = 'غير نشط';
        }

        return [
            'id' => $this->id,
            'comment_text' => $this->comment_text,
            'rateing' => $this->rateing,
            'status' => $status,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at->diffForHumans(),
            'updated_at' => (string) $this->updated_at,
            'user' => new UserResource($this->user),
            'product' => new ProductResource($this->product),
            'store' => $this->store !== null ? new StoreResource($this->store) : new StoreResource($this->user->store),
        ];
    }
}
