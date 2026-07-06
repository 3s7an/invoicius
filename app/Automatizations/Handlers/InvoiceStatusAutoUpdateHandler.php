<?php

declare(strict_types=1);

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\DTOs\AutomatizationResultDTO;
use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Services\InvoiceStatusAutoUpdateService;

class InvoiceStatusAutoUpdateHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceStatusAutoUpdateService $invoiceStatusAutoUpdateService,
    ) {}

    public function type(): AutomatizationType
    {
        return AutomatizationType::InvoiceStatusAutoUpdate;
    }

    public function handle(Automatization $automatization): AutomatizationResultDTO
    {
        $updateData = $this->invoiceStatusAutoUpdateService->updateOverdueInvoices($automatization);

        return AutomatizationResultDTO::success($updateData);
    }
}
