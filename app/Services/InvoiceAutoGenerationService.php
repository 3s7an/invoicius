<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\InvoiceDTO;
use App\Exceptions\Automatization\AutomatizationException;
use App\Exceptions\DuplicateInvoiceNumberException;
use App\Models\Automatization;
use App\Models\Recipient;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;

class InvoiceAutoGenerationService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {
    }

    /**
     * @return array{
     *   invoice_id: int,
     *   invoice_number: string|null,
     *   user_email: string,
     *   recipient_name: string|null,
     * }
     */
    public function buildGenerationData(Automatization $automatization): array
    {
        $userId = $automatization->user_id;

        try {
            $user = User::with('defaultVatType')->findOrFail($userId);
            $recipient = Recipient::forUser($userId)->findOrFail($automatization->recipient_id);
        } catch (ModelNotFoundException) {
            throw new AutomatizationException(__('messages.automatization_missing_user_or_recipient'));
        }

        $data = InvoiceDTO::fromAutomatization(
            $automatization,
            $user,
            $recipient,
            $this->invoiceService->getSuggestedNumber($userId),
        );

        try {
            $invoice = $this->invoiceService->createInvoice($data);
        } catch (DuplicateInvoiceNumberException|UniqueConstraintViolationException $e) {
            throw new AutomatizationException($e instanceof DuplicateInvoiceNumberException
                ? $e->getMessage()
                : __('messages.invoice_number_exists'), $e->getCode(), $e);
        }

        return [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->number,
            'user_email' => $automatization->user->email,
            'recipient_name' => $invoice->recipient_name,
        ];
    }
}
