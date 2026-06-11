<?php

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\DTOs\AutomatizationResultDTO;
use App\Models\Automatization;
use App\Services\InvoiceAutoGenerationService;

class InvoiceAutoGenerationHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceAutoGenerationService $invoiceAutoGenerationService,
    ) {
    }

    public function type(): string
    {
        return 'invoice_auto_gen';
    }

    public function handle(Automatization $automatization): AutomatizationResultDTO
    {
        $invoice = $this->invoiceAutoGenerationService->createFromAutomatization($automatization);

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
