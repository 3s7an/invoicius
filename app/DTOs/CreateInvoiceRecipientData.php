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
}
