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

    public function buildGenerationData(Automatization $automatization): array
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

        $invoice = $this->invoiceService->createInvoice(
            InvoiceDTO::fromAutomatization(
                $automatization,
                $user,
                $recipient,
                $suggestedNumber,
            )
        );

        return [
            'invoice_id' => $invoice->id, 
            'invoice_number' => $invoice->number,
            'user_email' => $automatization->user->email, 
            'recipient_name' => $invoice->recipient_name
        ];
    }
}
