<?php

namespace App\Contracts;

use App\DTOs\CreateInvoiceData;
use App\Models\Invoice;
use Illuminate\Support\Collection;

interface InvoiceServiceInterface
{
    public function getInvoices(int $userId): Collection;

    /**
     * @return array{total_invoiced: float, paid: float, awaiting: float, overdue: float}
     */
    public function getInvoiceStats(int $userId): array;

    public function getRecipients(int $userId): Collection;

    public function getSuggestedNumber(int $userId): string;

    public function createInvoice(CreateInvoiceData $data): Invoice;

    public function updateStatus(Invoice $invoice, int $invoiceStatusId): void;

    public function delete(Invoice $invoice): void;

    /**
     * @return \Spatie\LaravelPdf\PdfBuilder
     */
    public function getPdfDownloadResponse(Invoice $invoice);

    public function getIndexData(int $userId): array;

    public function getCreateFormData(int $userId, ?int $createdRecipientId): array;
}
