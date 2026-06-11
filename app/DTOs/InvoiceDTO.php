<?php

namespace App\DTOs;

use Carbon\Carbon;

final readonly class InvoiceDTO
{
    public function __construct(
        public int $userId,
        public string $number,
        public string $variableSymbol,
        public Carbon $issueDate,
        public Carbon $dueDate,
        public int $currencyId,
        public ?int $recipientId,
        public InvoiceRecipientDTO $recipient,
        /** @var array<int, InvoiceItemDTO> */
        public array $items,
    ) {
    }

    /**
     * @param array<string, mixed> $validated
     */
    public static function fromValidated(array $validated, int $userId): self
    {
        return new self(
            userId: $userId,
            number: $validated['number'],
            variableSymbol: $validated['variable_symbol'],
            issueDate: Carbon::parse($validated['issue_date']),
            dueDate: Carbon::parse($validated['due_date']),
            currencyId: (int) $validated['currency_id'],
            recipientId: $validated['recipient_id'] === null ? null : (int) $validated['recipient_id'],
            recipient: InvoiceRecipientDTO::fromArray($validated['recipient']),
            items: array_map(
                fn (array $row) => InvoiceItemDTO::fromArray($row),
                $validated['items'],
            ),
        );
    }
}
