<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sliderUpdateRequest extends FormRequest
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
          'slider1' => ['nullable', 'required_without_all:slider2,slider3', 'max:1048'],
            'sliderstatus1' => 'required|in:active,not_active',
            'slider2' => ['nullable', 'required_without_all:slider1,slider3', 'max:1048'],
            'sliderstatus2' => 'required|in:active,not_active',
            'slider3' => ['nullable', 'required_without_all:slider2,slider1', 'max:1048'],
            'sliderstatus3' => 'required|in:active,not_active',
        ];
    }
}
