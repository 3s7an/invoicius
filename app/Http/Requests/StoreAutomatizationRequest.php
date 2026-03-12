<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAutomatizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'recipient_id' => [
                'required',
                'integer',
                Rule::exists('recipients', 'id')->where('user_id', $this->user()?->id),
            ],
            'type' => ['required', 'string', 'max:100'],
            'date_trigger' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}
