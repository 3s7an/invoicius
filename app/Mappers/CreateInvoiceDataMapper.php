<?php

namespace App\Mappers;

use App\DTOs\CreateInvoiceData;
use Carbon\Carbon;

final class CreateInvoiceDataMapper
{
    public function __construct(
        private readonly CreateInvoiceRecipientDataMapper $recipientDataMapper,
        private readonly CreateInvoiceItemDataMapper $itemDataMapper,
    ) {
    }

    /**
     * @param array<string, mixed> $validated
     */
    public function fromValidated(array $validated, int $userId): CreateInvoiceData
    {
        return new CreateInvoiceData(
            userId: $userId,
            number: $validated['number'],
            variableSymbol: $validated['variable_symbol'],
            issueDate: Carbon::parse($validated['issue_date']),
            dueDate: Carbon::parse($validated['due_date']),
            currencyId: (int) $validated['currency_id'],
            recipientId: $validated['recipient_id'] === null ? null : (int) $validated['recipient_id'],
            recipient: $this->recipientDataMapper->fromArray($validated['recipient']),
            items: array_map(
                fn (array $row) => $this->itemDataMapper->fromArray($row),
                $validated['items'],
            ),
        );
    }
}
