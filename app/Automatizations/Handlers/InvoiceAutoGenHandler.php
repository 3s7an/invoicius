<?php

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\Contracts\InvoiceServiceInterface;
use App\DTOs\AutomatizationResult;
use App\Models\Automatization;

class InvoiceAutoGenHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceServiceInterface $invoiceService,
    ) {
    }

    public function type(): string
    {
        return 'invoice_auto_gen';
    }

    public function handle(Automatization $automatization): AutomatizationResult
    {
        if (! $automatization->recipient_id) {
            return new AutomatizationResult(
                success: false,
                error: 'No recipient assigned to automatization.',
            );
        }

        if (! $automatization->recipient) {
            return new AutomatizationResult(
                success: false,
                error: "Recipient #{$automatization->recipient_id} not found.",
            );
        }

        $invoice = $this->invoiceService->generateFromAutomatization(
            $automatization->user_id,
            $automatization->recipient_id,
        );

        return new AutomatizationResult(
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
