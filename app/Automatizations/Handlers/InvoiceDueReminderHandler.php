<?php

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\DTOs\AutomatizationResultDTO;
use App\Models\Automatization;
use App\Services\InvoiceDueReminderService;

class InvoiceDueReminderHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceDueReminderService $invoiceDueReminderService,
    ) {
    }

    public function type(): string
    {
        return 'invoice_due_reminder';
    }

    public function handle(Automatization $automatization): AutomatizationResultDTO
    {   
        $reminderData = $this->invoiceDueReminderService->buildReminderData($automatization);
        
        return AutomatizationResultDTO::success($reminderData);
    }
}
