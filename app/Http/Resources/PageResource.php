<?php

namespace App\Http\Resources;

use App\Http\Resources\UserPageResource;
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
            $status = __('message.publish');
        } else {
            $status = __('message.forbidden');
        }
        $categoryIds = empty($this->page_categories)? array():  $this->page_categories->pluck('id')->toArray();
        return [
            'id' => $this->id,
            'title' => $this->title === "null" ? "" : $this->title,
            'page_content' => $this->page_content === null ? "" : $this->page_content,
            'seo_title' => $this->seo_title === "null" ? "" : $this->seo_title,
            'seo_link' => $this->store_id == null ?( $this->postcategory != null?'https://atlbha.sa/blog/'. $this->id . '/'. preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $this->title): 'https://atlbha.sa/terms/'. preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $this->title).'/'.$this->id):( in_array(1, $categoryIds)?'https: //' . $this->store->domain . '/blog/'.$this->id.'/'. preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $this->title):'https: //' . $this->store->domain . '/terms/'.preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $this->title).'/'.$this->id),
            'seo_desc' => $this->seo_desc === "null" ? "" : $this->seo_desc,
            'page_desc' => $this->page_desc === "null" ? "" : $this->page_desc,
            'image' => $this->image != null ? $this->image : "",
            'altImage' => $this->altImage,
            'default_page' => $this->default_page,
            'tags' =>$this->tags != null ? explode(',',$this->tags): array(),
            'store' => new StoreResource($this->store),
            'postCategory' => $this->postcategory != null ? new PostCategoryResource($this->postcategory):null,
            'user' => new UserPageResource($this->user),
            'pageCategory' =>empty($this->page_categories) ? array(): Page_categoryResource::collection($this->page_categories),
            // 'page_url' => $this->store_id == null ? 'https://atlbha.sa/post/' . preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $this->title) : 'https: //template.atlbha.sa/' . $this->store->domain . '/site/SitePages/' . $this->id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'status' => $status,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
        ];
    }
}

