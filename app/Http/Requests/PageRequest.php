<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:26',
            'page_desc' => 'required',
            'page_content' => 'required',
            'seo_title' => 'nullable',
            'seo_link' => 'nullable',
            'seo_desc' => 'nullable',
            'tags' => 'nullable',
            'altImage' => 'nullable',
            'pageCategory' => ['nullable', 'array'],
            'pageCategory.*' => 'required'
        ];
    }
}
