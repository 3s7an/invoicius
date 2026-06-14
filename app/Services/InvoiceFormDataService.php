<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\InvoiceStatusResource;
use App\Http\Resources\RecipientResource;
use App\Http\Resources\VatTypeResource;
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
            'invoices'         => InvoiceResource::collection(
                $this->invoiceService->getInvoices($userId)
            ),
            'invoice_stats'    => $this->invoiceService->getInvoiceStats($userId),
            'invoice_statuses' => InvoiceStatusResource::collection(
                InvoiceStatus::orderBy('id')->get()
            ),
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
            'suggested_number'      => $this->invoiceService->getSuggestedNumber($userId),
            'preselected_recipient' => $preselectedRecipient
                ? RecipientResource::make($preselectedRecipient)
                : null,
            'default_currency_id'   => $user?->currency_id,
        ];
    }

    public function forEdit(Invoice $invoice, int $userId): array
    {
        $invoice->load(['items', 'invoiceStatus']);

        return [
            ...$this->sharedFormReferenceData($userId),
            'invoice'             => InvoiceResource::make($invoice),
            'default_currency_id' => $invoice->currency_id,
        ];
    }

    /**
     * @return array{recipients: mixed, currencies: mixed, vat_types: mixed}
     */
    private function sharedFormReferenceData(int $userId): array
    {
        return [
            'recipients' => RecipientResource::collection(
                $this->recipientService->listForUser($userId)
            ),
            'currencies' => CurrencyResource::collection(
                Currency::orderBy('name')->get()
            ),
            'vat_types'  => VatTypeResource::collection(
                VatType::orderBy('code')->get()
            ),
        ];
    }
}
