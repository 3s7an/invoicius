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
        $invoice_data = $this->invoiceAutoGenerationService->buildGenerationData($automatization);

        return AutomatizationResultDTO::success($invoice_data);
    }
}
