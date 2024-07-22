<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class banarUpdateRequest extends FormRequest
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
           'banar1' => ['nullable', 'required_without_all:banar2,banar3', 'max:1048'],
            'banarstatus1' => 'required|in:active,not_active',
            'banar2' => ['nullable', 'required_without_all:banar3,banar1', 'max:1048'],
            'banarstatus2' => 'required|in:active,not_active',
            'banar3' => ['nullable', 'required_without_all:banar2,banar1', 'max:1048'],
            'banarstatus3' => 'required|in:active,not_active',
        ];
    }
}
