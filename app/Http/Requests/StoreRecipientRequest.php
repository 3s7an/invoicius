<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'street_num' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
            'state' => ['required', 'string', 'max:255'],
            'ico' => ['nullable', 'string', 'max:50'],
            'dic' => ['nullable', 'string', 'max:50'],
            'ic_dph' => ['nullable', 'string', 'max:50'],
            'iban' => ['nullable', 'string', 'max:34'],
        ];
    }
}
