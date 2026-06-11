<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Recipient;
use App\Models\User;
use App\Models\VatType;

class InvoiceFormDataService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly RecipientService $recipientService,
    ) {
    }

    public function forIndex(int $userId): array
    {
        return [
            'invoices' => $this->invoiceService->getInvoices($userId),
            'invoice_stats' => $this->invoiceService->getInvoiceStats($userId),
            'invoice_statuses' => InvoiceStatus::orderBy('id')->get(['id', 'code', 'name']),
        ];
    }

    public function forCreate(int $userId, ?int $createdRecipientId): array
    {
        $user = User::find($userId);
        $preselectedRecipient = null;

        if ($createdRecipientId) {
            $preselectedRecipient = Recipient::forUser($userId)->find($createdRecipientId);
        }

        return [
            ...$this->sharedFormReferenceData($userId),
            'suggested_number' => $this->invoiceService->getSuggestedNumber($userId),
            'preselected_recipient' => $preselectedRecipient,
            'default_currency_id' => $user?->currency_id,
        ];
    }

    public function forEdit(Invoice $invoice, int $userId): array
    {
        $invoice->load(['items', 'recipient']);

        return [
            ...$this->sharedFormReferenceData($userId),
            'invoice' => $invoice,
            'default_currency_id' => $invoice->currency_id,
        ];
    }

    /**
     * @return array{recipients: \Illuminate\Support\Collection, currencies: \Illuminate\Support\Collection, vat_types: \Illuminate\Support\Collection}
     */
    private function sharedFormReferenceData(int $userId): array
    {
        return [
            'recipients' => $this->recipientService->listForUser($userId),
            'currencies' => Currency::orderBy('name')->get(['id', 'name', 'symbol']),
            'vat_types' => VatType::orderBy('code')->get(['id', 'code', 'rate']),
        ];
    }
}
