<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            $status = 'تم النشر';
        } else {
            $status = 'محظور';
        }
        return [
            'id' => $this->id,
            'title' => $this->title === "null" ? "" : $this->title,
            'page_content' => $this->page_content === null ? "" : $this->page_content,
            'seo_title' => $this->seo_title === "null" ? "" : $this->seo_title,
            'seo_link' => $this->seo_link === "null" ? "" : $this->seo_link,
            'seo_desc' => $this->seo_desc === "null" ? "" : $this->seo_desc,
            'page_desc' => $this->page_desc === "null" ? "" : $this->page_desc,
            'image' => $this->image != null ? $this->image : "",
            'altImage' => $this->altImage,
            'tags' =>$this->tags !== ""? explode(',', $this->tags):array(),
            'store' => new StoreResource($this->store),
            'postCategory' => new PostCategoryResource($this->postcategory),
            'user' => new UserResource($this->user),
            'pageCategory' => Page_categoryResource::collection($this->page_categories),
            'page_url' => $this->store_id == null ? 'https://atlbha.com/pages/' . $this->id : 'https: //template.atlbha.com/' . $this->store->domain . '/site/SitePages/' . $this->id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'status' => $status,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
        ];
    }
}
