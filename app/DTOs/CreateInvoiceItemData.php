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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            quantity: (float) ($data['quantity'] ?? 0),
            unitPrice: (float) ($data['unit_price'] ?? 0),
            unit: $data['unit'] ?? 'pcs',
            vatTypeId: isset($data['vat_type_id']) && $data['vat_type_id'] !== '' ? (int) $data['vat_type_id'] : null,
        );
    }
}
