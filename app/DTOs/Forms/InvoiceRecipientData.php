<?php

declare(strict_types=1);

namespace App\DTOs\Forms;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final readonly class InvoiceRecipientData
{
    public function __construct(
        public ?string $recipient_name,
        public ?string $recipient_street,
        public ?string $recipient_street_num,
        public ?string $recipient_city,
        public ?string $recipient_state,
        public ?string $recipient_ico,
        public ?string $recipient_dic,
        public ?string $recipient_ic_dph,
        public ?string $recipient_iban,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            recipient_name:        $data['recipient_name'] ?? null,
            recipient_street:      $data['recipient_street'] ?? null,
            recipient_street_num:  $data['recipient_street_num'] ?? null,
            recipient_city:        $data['recipient_city'] ?? null,
            recipient_state:       $data['recipient_state'] ?? null,
            recipient_ico:         $data['recipient_ico'] ?? null,
            recipient_dic:         $data['recipient_dic'] ?? null,
            recipient_ic_dph:      $data['recipient_ic_dph'] ?? null,
            recipient_iban:        $data['recipient_iban'] ?? null,
        );
    }
}
