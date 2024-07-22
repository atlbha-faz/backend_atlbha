<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'comment_text' => 'required|string|max:255',
            'rateing' => 'required|numeric',
            'comment_for' => 'required|in:product,store',
            'product_id' => 'required|exists:products,id'
        ];
    }
}
