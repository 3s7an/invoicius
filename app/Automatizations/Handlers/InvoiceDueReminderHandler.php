<?php

namespace App\Automatizations\Handlers;

use App\Contracts\AutomatizationHandlerInterface;
use App\Contracts\InvoiceServiceInterface;
use App\DTOs\AutomatizationResult;
use App\Models\Automatization;
use App\Models\InvoiceStatus;

class InvoiceDueReminderHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceServiceInterface $invoiceService,
    ) {
    }

    public function type(): string
    {
        return 'invoice_due_reminder';
    }

    public function handle(Automatization $automatization): AutomatizationResult
    {
        if (! $automatization->user_id || ! $automatization->user) {
            return new AutomatizationResult(
                success: false,
                error: 'No user assigned to automatization.',
            );
        }

        $offsetDays = (int) ($automatization->due_offset_days ?? 0);
        $targetDate = now()->startOfDay()->addDays($offsetDays);

        $allInvoices = $this->invoiceService
            ->getInvoices($automatization->user_id)
            ->loadMissing('invoiceStatus');

        $dueInvoices = $allInvoices->filter(function ($invoice) use ($targetDate) {
            if (! $invoice->due_date) {
                return false;
            }

            if (! $invoice->invoiceStatus) {
                return false;
            }

            $code = $invoice->invoiceStatus->code;

            if ($code === InvoiceStatus::CODE_PAID || $code === InvoiceStatus::CODE_DRAFT) {
                return false;
            }

            return $invoice->due_date->isSameDay($targetDate);
        })->values();

        $invoiceData = $dueInvoices->map(function ($invoice) use ($targetDate, $offsetDays) {
            $recipientName = $invoice->recipient_name;

            return [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'recipient_name' => $recipientName,
                'due_date' => optional($invoice->due_date)->toDateString(),
                'status_code' => $invoice->invoiceStatus?->code,
                'status_name' => $invoice->invoiceStatus?->name,
                'days_from_today' => $offsetDays,
                'target_date' => $targetDate->toDateString(),
            ];
        })->all();

        return new AutomatizationResult(
            success: true,
            data: [
                'user_email' => $automatization->user->email,
                'due_offset_days' => $offsetDays,
                'target_date' => $targetDate->toDateString(),
                'invoices' => $invoiceData,
            ],
        );
    }
}

