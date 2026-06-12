<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\AutomatizationType;
use App\Http\Requests\Concerns\ResolvesAutomatizationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutomatizationRequest extends FormRequest
{
    use ResolvesAutomatizationType;

    public function rules(): array
    {
        $type = $this->automatizationType();
        $requiresItemNames = $type === AutomatizationType::InvoiceAutoGen;

        return [
            'recipient_id' => [
                Rule::requiredIf($requiresItemNames),
                Rule::excludeIf(! $requiresItemNames),
                'nullable',
                'integer',
                Rule::exists('recipients', 'id')->where('user_id', $this->user()?->id),
            ],
            'type' => ['sometimes', Rule::enum(AutomatizationType::class)],
            'date_trigger' => ['nullable', 'date'],
            'due_offset_days' => [
                Rule::requiredIf($type === AutomatizationType::InvoiceDueReminder),
                Rule::excludeIf($type !== AutomatizationType::InvoiceDueReminder),
                'nullable',
                'integer',
                'min:-365',
                'max:365',
            ],
            'is_active' => ['nullable', 'boolean'],
            'item_names' => [
                Rule::requiredIf($requiresItemNames),
                Rule::excludeIf(! $requiresItemNames),
                'nullable',
                'array',
                'min:1',
                'max:20',
            ],
            'item_names.*' => ['required', 'string', 'max:255'],
        ];
    }
}
