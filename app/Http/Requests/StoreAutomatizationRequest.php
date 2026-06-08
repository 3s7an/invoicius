<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAutomatizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient_id' => [
                Rule::requiredIf(fn () => $this->input('type') === 'invoice_auto_gen'),
                'nullable',
                'integer',
                Rule::exists('recipients', 'id')->where('user_id', $this->user()?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'date_trigger' => ['required', 'date', 'after_or_equal:today'],
            'due_offset_days' => [
                Rule::requiredIf(fn () => $this->input('type') === 'invoice_due_reminder'),
                'nullable',
                'integer',
                'min:-365',
                'max:365',
            ],
            'item_names' => [
                Rule::requiredIf(fn () => $this->input('type') === 'invoice_auto_gen'),
                'nullable',
                'array',
                'min:1',
                'max:20',
            ],
            'item_names.*' => ['required', 'string', 'max:255'],
        ];
    }
}
