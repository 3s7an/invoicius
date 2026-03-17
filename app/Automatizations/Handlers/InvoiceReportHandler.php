<?php

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\Contracts\InvoiceServiceInterface;
use App\DTOs\AutomatizationResult;
use App\Models\Automatization;
use Carbon\Carbon;

class InvoiceReportHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceServiceInterface $invoiceService,
    ) {
    }

    public function type(): string
    {
        return 'invoice_report';
    }

    public function handle(Automatization $automatization): AutomatizationResult
    {
        if (! $automatization->user_id || ! $automatization->user) {
            return new AutomatizationResult(
                success: false,
                error: 'No user assigned to automatization.',
            );
        }

        $userId = $automatization->user_id;

        $periodStart = now()->subMonthNoOverflow()->startOfMonth();
        $periodEnd = now()->subMonthNoOverflow()->endOfMonth()->endOfDay();

        $allInvoices = $this->invoiceService
            ->getInvoices($userId)
            ->loadMissing('invoiceStatus');

        $monthlyInvoices = $allInvoices->filter(function ($invoice) use ($periodStart, $periodEnd) {
            if (! $invoice->issue_date) {
                return false;
            }

            return $invoice->issue_date->between($periodStart, $periodEnd);
        });

        $stats = $this->calculateMonthlyStats($monthlyInvoices, $periodEnd);

        $invoiceData = $monthlyInvoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'varsym' => $invoice->varsym,
                'issue_date' => optional($invoice->issue_date)->toDateString(),
                'due_date' => optional($invoice->due_date)->toDateString(),
                'total_price' => (float) $invoice->total_price,
                'status_code' => $invoice->invoiceStatus?->code,
                'status_name' => $invoice->invoiceStatus?->name,
                'recipient_name' => $invoice->recipient_name,
                'recipient_id' => $invoice->recipient_id,
            ];
        })->values()->all();

        return new AutomatizationResult(
            success: true,
            data: [
                'user_id' => $userId,
                'period' => [
                    'from' => $periodStart->toDateString(),
                    'to' => $periodEnd->toDateString(),
                ],
                'stats' => $stats,
                'invoices' => $invoiceData,
            ],
        );
    }

    /**
     * @param \Illuminate\Support\Collection $invoices
     * @return array{total_invoiced: float, paid: float, awaiting: float, overdue: float}
     */
    private function calculateMonthlyStats($invoices, Carbon $referenceDate): array
    {
        $totalInvoiced = (float) $invoices->sum('total_price');

        $paid = (float) $invoices
            ->filter(function ($invoice) {
                return $invoice->invoiceStatus?->code === 'paid';
            })
            ->sum('total_price');

        $overdue = (float) $invoices
            ->filter(function ($invoice) use ($referenceDate) {
                if (! $invoice->due_date) {
                    return false;
                }

                $statusCode = $invoice->invoiceStatus?->code;

                if ($statusCode === 'paid' || $statusCode === 'draft') {
                    return false;
                }

                return $invoice->due_date->lt($referenceDate);
            })
            ->sum('total_price');

        $awaiting = max(
            0.0,
            $totalInvoiced - $paid - $overdue
        );

        return [
            'total_invoiced' => $totalInvoiced,
            'paid' => $paid,
            'awaiting' => (float) $awaiting,
            'overdue' => $overdue,
        ];
    }
}

