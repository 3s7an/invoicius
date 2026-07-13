<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatusCode;
use App\Models\Automatization;
use App\Models\InvoiceStatus;

class InvoiceStatusAutoUpdateService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {}

    /**
     * @return array{
     *   target_date: string,
     *   updated_count: int,
     *   invoices: array<int, array<string, mixed>>
     * }
     */
    public function updateOverdueInvoices(Automatization $automatization): array
    {
        $today = today();
        $overdueStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Overdue);

        $sentInvoicesPastDue = $this->invoiceService
            ->getInvoices($automatization->user_id)
            ->filter(function ($invoice) use ($today): bool {
                if (! $invoice->due_date || ! $invoice->invoiceStatus) {
                    return false;
                }

                return $invoice->invoiceStatus->code === InvoiceStatusCode::Sent->value
                    && $invoice->due_date->lt($today);
            })
            ->values();

        $updatedInvoices = $sentInvoicesPastDue->map(function ($invoice) use ($overdueStatus): array {
            $data = [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'recipient_name' => $invoice->recipient_name,
                'due_date' => $invoice->due_date?->toDateString(),
            ];

            $invoice->update(['invoice_status_id' => $overdueStatus?->id]);

            return $data;
        })->all();

        return [
            'target_date' => $today->toDateString(),
            'updated_count' => count($updatedInvoices),
            'invoices' => $updatedInvoices,
        ];
    }
}
