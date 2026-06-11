<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'company_logo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
