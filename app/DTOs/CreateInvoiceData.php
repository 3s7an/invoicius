<?php

namespace App\DTOs;

use Carbon\Carbon;

final readonly class CreateInvoiceData
{
    public function __construct(
        public int $userId,
        public string $number,
        public string $variableSymbol,
        public Carbon $issueDate,
        public Carbon $dueDate,
        public int $currencyId,
        public ?int $recipientId,
        public CreateInvoiceRecipientData $recipient,
        /** @var array<int, CreateInvoiceItemData> */
        public array $items,
    ) {
    }
}
