<?php

declare(strict_types=1);

namespace App\DTOs\Forms;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final readonly class InvoiceItemData
{
    public function __construct(
        public string $name,
        public float  $quantity,
        public string $unit,
        public float  $unit_price,
        public ?int   $vat_type_id,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name:        $data['name'],
            quantity:    (float) $data['quantity'],
            unit:        $data['unit'],
            unit_price:  (float) $data['unit_price'],
            vat_type_id: $data['vat_type_id'] !== null ? (int) $data['vat_type_id'] : null,
        );
    }
}
