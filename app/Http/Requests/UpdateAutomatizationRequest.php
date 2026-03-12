<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutomatizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('update', $this->route('automatization'));
    }

    public function rules(): array
    {
        return [
            'recipient_id' => [
                'nullable',
                'integer',
                Rule::exists('recipients', 'id')->where('user_id', $this->user()?->id),
            ],
            'type' => ['nullable', 'string', 'max:100'],
            'date_trigger' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
