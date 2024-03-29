<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
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
            'id' => $this->id,
            'primaryBg' => $this->primaryBg,
            'secondaryBg' => $this->secondaryBg,
            'headerBg' => $this->headerBg,
            'layoutBg' => $this->layoutBg,
            'iconsBg' => $this->iconsBg,
            // 'productBorder' => $this->productBorder,
            // 'productBg' => $this->productBg,
            // 'filtersBorder' => $this->filtersBorder,
            // 'filtersBg' => $this->filtersBg,
            // 'mainButtonBg' => $this->mainButtonBg,
            // 'mainButtonBorder' => $this->mainButtonBorder,
            // 'subButtonBg' => $this->subButtonBg,
            // 'subButtonBorder' => $this->subButtonBorder,
            'footerBorder' => $this->footerBorder,
            'footerBg' => $this->footerBg,
            'fontColor' => $this->fontColor,
            'store' => new StoreResource($this->store),
        ];
    }
}
