<?php

namespace App\Services;

use App\DTOs\InvoiceDTO;
use App\Models\Automatization;
use App\Models\Invoice;
use App\Models\Recipient;
use App\Models\User;

class InvoiceAutoGenerationService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {
    }

    public function createFromAutomatization(Automatization $automatization): Invoice
    {
        $userId = $automatization->user_id;

        $user = User::with('defaultVatType')->findOrFail($userId);
        $recipient = Recipient::forUser($userId)->findOrFail($automatization->recipient_id);
        $suggestedNumber = $this->invoiceService->getSuggestedNumber($userId);

        $data = InvoiceDTO::fromAutomatization(
            $automatization,
            $user,
            $recipient,
            $suggestedNumber,
        );

        return $this->invoiceService->createInvoice($data);
    }
}
