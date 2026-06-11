<?php

namespace App\Http\Requests;

use App\Enums\AutomatizationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutomatizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient_id' => [
                'nullable',
                'integer',
                Rule::exists('recipients', 'id')->where('user_id', $this->user()?->id),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', Rule::enum(AutomatizationType::class)],
            'date_trigger' => ['nullable', 'date'],
            'due_offset_days' => ['nullable', 'integer', 'min:-365', 'max:365'],
            'is_active' => ['nullable', 'boolean'],
            'item_names' => [
                Rule::requiredIf(fn () => $this->enum('type', AutomatizationType::class) === AutomatizationType::InvoiceAutoGen),
                'nullable',
                'array',
                'min:1',
                'max:20',
            ],
            'item_names.*' => ['required', 'string', 'max:255'],
        ];
    }
}
