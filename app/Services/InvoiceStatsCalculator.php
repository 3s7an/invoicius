<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatusCode;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InvoiceStatsCalculator
{
    /**
     * Stats for a pre-filtered invoice collection (dashboard, monthly report, etc.).
     *
     * @param Collection<int, \App\Models\Invoice> $invoices
     * @return array{total_invoiced: float, paid: float, awaiting: float, overdue: float, draft: float}
     */
    public function calculateForCollection(Collection $invoices, Carbon $referenceDate): array
    {
        $totalInvoiced = (float) $invoices->sum('total_price');

        $paid = (float) $invoices
            ->filter(fn (Invoice $invoice): bool => $invoice->invoiceStatus?->code === InvoiceStatusCode::Paid->value)
            ->sum('total_price');

        $draft = (float) $invoices
            ->filter(fn (Invoice $invoice): bool => $invoice->invoiceStatus?->code === InvoiceStatusCode::Draft->value)
            ->sum('total_price');

        $overdue = (float) $invoices
            ->filter(function (Invoice $invoice) use ($referenceDate) {
                if (! $invoice->due_date) {
                    return false;
                }

                $statusCode = $invoice->invoiceStatus?->code;

                if ($statusCode === InvoiceStatusCode::Paid->value || $statusCode === InvoiceStatusCode::Draft->value) {
                    return false;
                }

                return $invoice->due_date->lt($referenceDate);
            })
            ->sum('total_price');

        $awaiting = max(0.0, $totalInvoiced - $paid - $draft - $overdue);

        return [
            'total_invoiced' => $totalInvoiced,
            'paid' => $paid,
            'awaiting' => $awaiting,
            'overdue' => $overdue,
            'draft' => $draft,
        ];
    }
}
