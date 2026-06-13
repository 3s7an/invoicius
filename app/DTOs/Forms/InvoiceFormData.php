<?php

declare(strict_types=1);

namespace App\DTOs\Forms;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final readonly class InvoiceFormData
{
    public function __construct(
        public string               $number,
        public string               $variable_symbol,
        public string               $issue_date,
        public string               $due_date,
        public int                  $currency_id,
        public ?int                 $recipient_id,
        public InvoiceIssuerData    $issuer,
        public InvoiceRecipientData $recipient,
        /** @var InvoiceItemData[] */
        public array                $items,
    ) {}

    /**
     * @param array<string, mixed> $validated
     */
    public static function fromValidated(array $validated): self
    {
        return new self(
            number:          $validated['number'],
            variable_symbol: $validated['variable_symbol'],
            issue_date:      $validated['issue_date'],
            due_date:        $validated['due_date'],
            currency_id:     (int) $validated['currency_id'],
            recipient_id:    $validated['recipient_id'] !== null
                                 ? (int) $validated['recipient_id']
                                 : null,
            issuer:          InvoiceIssuerData::fromArray($validated['issuer']),
            recipient:       InvoiceRecipientData::fromArray($validated['recipient']),
            items:           array_map(
                                 InvoiceItemData::fromArray(...),
                                 $validated['items'],
                             ),
        );
    }
}
