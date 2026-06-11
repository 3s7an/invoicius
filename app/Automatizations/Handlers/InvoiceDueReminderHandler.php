<?php

declare(strict_types=1);

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\DTOs\AutomatizationResultDTO;
use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Services\InvoiceDueReminderService;

class InvoiceDueReminderHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceDueReminderService $invoiceDueReminderService,
    ) {
    }

    public function type(): AutomatizationType
    {
        return AutomatizationType::InvoiceDueReminder;
    }

    public function handle(Automatization $automatization): AutomatizationResultDTO
    {   
        $reminderData = $this->invoiceDueReminderService->buildReminderData($automatization);
        
        return AutomatizationResultDTO::success($reminderData);
    }
}
