<?php

namespace App\DTOs;

final readonly class CreateInvoiceRecipientData
{
    public function __construct(
        public ?string $recipientName,
        public ?string $recipientStreet,
        public ?string $recipientStreetNum,
        public ?string $recipientCity,
        public ?string $recipientState,
        public ?string $recipientIco,
        public ?string $recipientDic,
        public ?string $recipientIcDph,
        public ?string $recipientIban,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            recipientName: $data['recipient_name'] ?? null,
            recipientStreet: $data['recipient_street'] ?? null,
            recipientStreetNum: $data['recipient_street_num'] ?? null,
            recipientCity: $data['recipient_city'] ?? null,
            recipientState: $data['recipient_state'] ?? null,
            recipientIco: $data['recipient_ico'] ?? null,
            recipientDic: $data['recipient_dic'] ?? null,
            recipientIcDph: $data['recipient_ic_dph'] ?? null,
            recipientIban: $data['recipient_iban'] ?? null,
        );
    }
}
