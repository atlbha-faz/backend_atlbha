<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseLiveRequest extends FormRequest
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
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
                'link' => 'required|string'
            ];
    
    }
}
