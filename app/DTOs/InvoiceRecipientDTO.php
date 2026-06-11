<?php

namespace App\DTOs;

use App\Models\Recipient;

final readonly class InvoiceRecipientDTO
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

    public static function fromModel(Recipient $recipient): self
    {
        return new self(
            recipientName: $recipient->company_name ?? $recipient->name,
            recipientStreet: $recipient->street,
            recipientStreetNum: $recipient->street_num,
            recipientCity: $recipient->city,
            recipientState: $recipient->state,
            recipientIco: $recipient->ico,
            recipientDic: $recipient->dic,
            recipientIcDph: $recipient->ic_dph,
            recipientIban: $recipient->iban,
        );
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
