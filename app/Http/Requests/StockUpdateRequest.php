<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StockUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:25',
            'description' => 'required|string',
            'less_qty' => ['nullable', 'numeric', 'gt:0'],
            'purchasing_price' => ['required', 'numeric', 'gt:0'],
            'selling_price' => ['required', 'numeric', 'gte:' . (int) $this->purchasing_price],
            'stock' => ['required', 'numeric', 'gt:0'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'images' => 'nullable|array',

            'SEOdescription' => 'nullable',
            'snappixel' => 'nullable|string',
            'tiktokpixel' => 'nullable|string',
            'twitterpixel' => 'nullable|string',
            'instapixel' => 'nullable|string',
            'short_description' => 'required|string|max:100',
            'robot_link' => 'nullable|string',
            'google_analytics' => 'nullable|url',
            'weight' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => ['nullable', 'array'],
            'subcategory_id.*' => ['nullable', 'numeric',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->join('categories', 'id', 'parent_id');
                }),
            ],
            'product_has_options' => 'nullable|in:0,1',
            'attribute' => 'array|required_if:product_has_options,1',
        ];
    }
}
