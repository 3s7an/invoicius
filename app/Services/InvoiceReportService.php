<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Automatization;

class InvoiceReportService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly InvoiceStatsCalculator $invoiceStatsCalculator,
    ) {
    }

    /**
     * @return array{
     *   user_id: int,
     *   user_email: string,
     *   period: array{from: string, to: string},
     *   stats: array{total_invoiced: float, paid: float, awaiting: float, overdue: float, draft: float},
     *   invoices: array<int, array<string, mixed>>
     * }
     */
    public function buildReportData(Automatization $automatization): array
    {
        $userId = $automatization->user_id;

        $periodStart = now()->subMonthNoOverflow()->startOfMonth();
        $periodEnd = now()->subMonthNoOverflow()->endOfMonth()->endOfDay();

        $monthlyInvoices = $this->invoiceService
            ->getInvoices($userId)
            ->loadMissing('invoiceStatus')
            ->filter(function ($invoice) use ($periodStart, $periodEnd) {
                if (! $invoice->issue_date) {
                    return false;
                }

                return $invoice->issue_date->between($periodStart, $periodEnd);
            });

        $stats = $this->invoiceStatsCalculator->calculateForCollection($monthlyInvoices, $periodEnd);

        $invoiceData = $monthlyInvoices
            ->map(fn ($invoice): array => [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'varsym' => $invoice->varsym,
                'issue_date' => $invoice->issue_date?->toDateString(),
                'due_date' => $invoice->due_date?->toDateString(),
                'total_price' => (float) $invoice->total_price,
                'status_code' => $invoice->invoiceStatus?->code,
                'status_name' => $invoice->invoiceStatus?->name,
                'recipient_name' => $invoice->recipient_name,
                'recipient_id' => $invoice->recipient_id,
            ])
            ->values()
            ->all();

        return [
            'user_id' => $userId,
            'user_email' => $automatization->user->email,
            'period' => [
                'from' => $periodStart->toDateString(),
                'to' => $periodEnd->toDateString(),
            ],
            'stats' => $stats,
            'invoices' => $invoiceData,
        ];
    }
}
