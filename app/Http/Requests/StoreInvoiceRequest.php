<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $items = $this->input('items', []);
        foreach ($items as $i => $item) {
            if (isset($item['vat_type_id']) && $item['vat_type_id'] === '') {
                $items[$i]['vat_type_id'] = null;
            }
        }
        $this->merge(['items' => $items]);
    }

    /**
     * Get the validation rules that apply to the request.
     * Note: issuer/issuer.name are for display only and are not persisted to the invoice.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('invoices', 'number')->where('user_id', $userId),
            ],
            'variable_symbol' => ['required', 'string', 'max:50'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'issuer' => ['required', 'array'],
            'issuer.name' => ['required', 'string', 'max:255'],
            'recipient_id' => ['nullable', 'integer', Rule::exists('recipients', 'id')->where('user_id', $this->user()?->id)],
            'recipient' => ['required', 'array'],
            'recipient.recipient_name' => ['required', 'string', 'max:255'],
            'recipient.recipient_street' => ['nullable', 'string', 'max:255'],
            'recipient.recipient_street_num' => ['nullable', 'string', 'max:50'],
            'recipient.recipient_city' => ['nullable', 'string', 'max:255'],
            'recipient.recipient_state' => ['nullable', 'string', 'max:255'],
            'recipient.recipient_ico' => ['nullable', 'string', 'max:20'],
            'recipient.recipient_dic' => ['nullable', 'string', 'max:20'],
            'recipient.recipient_ic_dph' => ['nullable', 'string', 'max:20'],
            'recipient.recipient_iban' => ['nullable', 'string', 'max:34'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0'],
            'items.*.unit' => ['nullable', 'string', 'max:20'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.vat_type_id' => ['nullable', 'integer', 'exists:vat_types,id'],
        ];
    }
}
