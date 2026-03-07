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

    /**
     * @param array<string, mixed> $validated
     */
    public static function fromValidated(array $validated, int $userId): self
    {
        $recipient = $validated['recipient'] ?? [];
        $items = [];
        foreach ($validated['items'] ?? [] as $i => $row) {
            $items[$i] = CreateInvoiceItemData::fromArray($row);
        }

        return new self(
            userId: $userId,
            number: $validated['number'],
            variableSymbol: $validated['variable_symbol'],
            issueDate: Carbon::parse($validated['issue_date']),
            dueDate: Carbon::parse($validated['due_date']),
            currencyId: (int) $validated['currency_id'],
            recipientId: isset($validated['recipient_id']) && $validated['recipient_id'] !== '' ? (int) $validated['recipient_id'] : null,
            recipient: CreateInvoiceRecipientData::fromArray($recipient),
            items: $items,
        );
    }
}
