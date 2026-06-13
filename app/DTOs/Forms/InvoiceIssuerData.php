<?php

declare(strict_types=1);

namespace App\DTOs\Forms;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final readonly class InvoiceIssuerData
{
    public function __construct(
        public ?string $name,
        public ?string $street,
        public ?string $street_num,
        public ?string $city,
        public ?string $zip,
        public ?string $state,
        public ?string $ico,
        public ?string $dic,
        public ?string $ic_dph,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name:       $data['name'] ?? null,
            street:     $data['street'] ?? null,
            street_num: $data['street_num'] ?? null,
            city:       $data['city'] ?? null,
            zip:        $data['zip'] ?? null,
            state:      $data['state'] ?? null,
            ico:        $data['ico'] ?? null,
            dic:        $data['dic'] ?? null,
            ic_dph:     $data['ic_dph'] ?? null,
        );
    }
}
