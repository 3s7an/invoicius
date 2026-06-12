<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\AutomatizationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAutomatizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient_id' => [
                Rule::requiredIf(fn () => $this->enum('type', AutomatizationType::class) === AutomatizationType::InvoiceAutoGen),
                'nullable',
                'integer',
                Rule::exists('recipients', 'id')->where('user_id', $this->user()?->id),
            ],
            'type' => ['required', Rule::enum(AutomatizationType::class)],
            'date_trigger' => ['required', 'date', 'after_or_equal:today'],
            'due_offset_days' => [
                Rule::requiredIf(fn () => $this->enum('type', AutomatizationType::class) === AutomatizationType::InvoiceDueReminder),
                'nullable',
                'integer',
                'min:-365',
                'max:365',
            ],
            'item_names' => [
                Rule::requiredIf(fn () => $this->enum('type', AutomatizationType::class) === AutomatizationType::InvoiceAutoGen),
                'nullable',
                'array',
                'min:0',
                'max:20',
            ],
            'item_names.*' => ['required', 'string', 'max:255'],
        ];
    }
}
