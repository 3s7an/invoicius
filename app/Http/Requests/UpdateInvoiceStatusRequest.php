<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'invoice_status_id' => ['required', 'integer', 'exists:invoice_statuses,id'],
        ];
    }
}
