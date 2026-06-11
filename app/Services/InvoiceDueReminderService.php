<?php

namespace App\Services;

use App\Models\Automatization;
use App\Models\InvoiceStatus;

class InvoiceDueReminderService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {
    }

    /**
     * @return array{
     *   user_email: string,
     *   due_offset_days: int,
     *   target_date: string,
     *   invoices: array<int, array<string, mixed>>
     * }
     */
    public function buildReminderData(Automatization $automatization): array
    {
        $offsetDays = (int) ($automatization->due_offset_days ?? 0);
        $targetDate = now()->startOfDay()->addDays($offsetDays);

        $allInvoices = $this->invoiceService
            ->getInvoices($automatization->user_id)
            ->loadMissing('invoiceStatus');

        $dueInvoices = $allInvoices->filter(function ($invoice) use ($targetDate) {
            if (! $invoice->due_date || ! $invoice->invoiceStatus) {
                return false;
            }

            if ($invoice->invoiceStatus->code === InvoiceStatus::CODE_PAID) {
                return false;
            }

            return $invoice->due_date->isSameDay($targetDate);
        })->values();

        $invoiceData = $dueInvoices->map(function ($invoice) use ($targetDate, $offsetDays) {
            return [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'recipient_name' => $invoice->recipient_name,
                'due_date' => optional($invoice->due_date)->toDateString(),
                'status_code' => $invoice->invoiceStatus?->code,
                'status_name' => $invoice->invoiceStatus?->name,
                'days_from_today' => $offsetDays,
                'target_date' => $targetDate->toDateString(),
            ];
        })->all();

        return [
            'user_email' => $automatization->user->email,
            'due_offset_days' => $offsetDays,
            'target_date' => $targetDate->toDateString(),
            'invoices' => $invoiceData,
        ];
    }
}
