<?php

declare(strict_types=1);

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\DTOs\AutomatizationResultDTO;
use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Services\InvoiceReportService;

class InvoiceReportHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceReportService $invoiceReportService,
    ) {
    }

    public function type(): AutomatizationType
    {
        return AutomatizationType::InvoiceReport;
    }

    public function handle(Automatization $automatization): AutomatizationResultDTO
    {   
        $reportData = $this->invoiceReportService->buildReportData($automatization);

        return AutomatizationResultDTO::success($reportData);
    }
}
