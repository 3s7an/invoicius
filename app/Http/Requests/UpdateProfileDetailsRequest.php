<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'street' => ['nullable', 'string', 'max:255'],
            'street_num' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:20'],
            'state' => ['nullable', 'string', 'max:255'],
            'ico' => ['nullable', 'string', 'max:20'],
            'dic' => ['nullable', 'string', 'max:20'],
            'ic_dph' => ['nullable', 'string', 'max:20'],
            'iban' => ['nullable', 'string', 'max:34'],
            'currency_id' => ['nullable', 'integer', 'exists:currencies,id'],
            'default_vat_type_id' => ['nullable', 'integer', 'exists:vat_types,id'],
            'company_logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'currency_id' => $this->input('currency_id') === '' || $this->input('currency_id') === null
                ? null
                : (int) $this->input('currency_id'),
            'default_vat_type_id' => $this->input('default_vat_type_id') === '' || $this->input('default_vat_type_id') === null
                ? null
                : (int) $this->input('default_vat_type_id'),
        ]);
    }
}

