<?php

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\Services\InvoiceService;
use App\DTOs\AutomatizationResultDTO;
use App\Models\Automatization;

class InvoiceAutoGenHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {
    }

    public function type(): string
    {
        return 'invoice_auto_gen';
    }

    public function handle(Automatization $automatization): AutomatizationResultDTO
    {
        $invoice = $this->invoiceService->generateFromAutomatization(
            $automatization->user_id,
            $automatization->recipient_id,
            $automatization->item_names ?? [],
        );

        return new AutomatizationResultDTO(
            success: true,
            data: [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->number,
                'user_email' => $automatization->user->email,
                'recipient_name' => $automatization->recipient->company_name
                    ?? $automatization->recipient->name,
            ],
        );
    }
}
