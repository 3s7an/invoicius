<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Automatization;
use App\Models\Currency;
use App\Models\Recipient;
use App\Models\User;
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

    public static function fromAutomatization(
        Automatization $automatization,
        User $user,
        Recipient $recipient,
        string $suggestedNumber,
    ): self {
        $defaultCurrencyId = $user->currency_id ?? Currency::orderBy('id')->value('id');
        $defaultVatTypeId = $user->default_vat_type_id;
        $itemNames = $automatization->item_names ?? [];

        if ($itemNames !== []) {
            $items = array_map(
                fn (string $name): \App\DTOs\InvoiceItemDTO => new InvoiceItemDTO(
                    name: $name,
                    quantity: 1,
                    unitPrice: 0,
                    unit: 'hrs',
                    vatTypeId: $defaultVatTypeId,
                ),
                $itemNames,
            );
        } else {
            $items = [
                new InvoiceItemDTO(
                    name: '',
                    quantity: 1,
                    unitPrice: 0,
                    unit: 'hrs',
                    vatTypeId: $defaultVatTypeId,
                ),
            ];
        }

        return new self(
            userId: $automatization->user_id,
            number: $suggestedNumber,
            variableSymbol: $suggestedNumber,
            issueDate: \Illuminate\Support\Facades\Date::today(),
            dueDate: \Illuminate\Support\Facades\Date::today()->addDays(14),
            currencyId: $defaultCurrencyId,
            recipientId: $automatization->recipient_id,
            recipient: InvoiceRecipientDTO::fromModel($recipient),
            items: $items,
        );
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
            issueDate: \Illuminate\Support\Facades\Date::parse($validated['issue_date']),
            dueDate: \Illuminate\Support\Facades\Date::parse($validated['due_date']),
            currencyId: (int) $validated['currency_id'],
            recipientId: $validated['recipient_id'] === null ? null : (int) $validated['recipient_id'],
            recipient: InvoiceRecipientDTO::fromArray($validated['recipient']),
            items: array_map(
                InvoiceItemDTO::fromArray(...),
                $validated['items'],
            ),
        );
    }
}
