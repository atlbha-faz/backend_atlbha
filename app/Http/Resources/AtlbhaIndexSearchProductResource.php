<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AtlbhaIndexSearchProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
     
        
            $domain = $this->store_id !== null ? $this->store->domain : 'atlbha';
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image'=> $this->cover,
            'url' => 'https://template.atlbha.com/' . $domain . '/shop/product/' . $this->id.'/'.preg_replace('/ /', '-',$this->name),
        ];
      
       
    }
}
