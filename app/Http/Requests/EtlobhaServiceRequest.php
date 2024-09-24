<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtlobhaServiceRequest extends FormRequest
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
            'email' => 'required|string|email',
            'phone_number' => 'required|string',
            'code' => 'nullable|string',
            'service_id' => 'required|array',
            'paymentype_id' => 'required_without:code',
            'service_reference' => 'required if:paymentype_id,5',
        ];
    }
}
