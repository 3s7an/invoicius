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
            recipientName: $data['recipient_name'],
            recipientStreet: $data['recipient_street'],
            recipientStreetNum: $data['recipient_street_num'],
            recipientCity: $data['recipient_city'],
            recipientState: $data['recipient_state'],
            recipientIco: $data['recipient_ico'],
            recipientDic: $data['recipient_dic'],
            recipientIcDph: $data['recipient_ic_dph'],
            recipientIban: $data['recipient_iban'],
        );
    }
}
