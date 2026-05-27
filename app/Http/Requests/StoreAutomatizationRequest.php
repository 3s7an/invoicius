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
            'type' => ['required', 'string', 'max:100'],
            'date_trigger' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}
