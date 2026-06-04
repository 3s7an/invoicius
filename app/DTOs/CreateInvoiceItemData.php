<?php

namespace App\DTOs;

final readonly class CreateInvoiceItemData
{
    public function __construct(
        public string $name,
        public float $quantity,
        public float $unitPrice,
        public string $unit,
        public ?int $vatTypeId,
    ) {
    }
}
