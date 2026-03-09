<?php

namespace App\Http\Requests;

use App\Models\InvoiceColor;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'invoice_color_id' => ['nullable', 'integer', 'exists:invoice_colors,id'],
            'company_logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'invoice_color_id' => $this->input('invoice_color_id') === '' || $this->input('invoice_color_id') === null
                ? (InvoiceColor::orderBy('id')->value('id') ?? 1)
                : (int) $this->input('invoice_color_id'),
        ]);
    }
}
