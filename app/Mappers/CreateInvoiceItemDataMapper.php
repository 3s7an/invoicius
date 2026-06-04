<?php

namespace App\Mappers;

use App\DTOs\CreateInvoiceItemData;

final class CreateInvoiceItemDataMapper
{
    /**
     * @param array<string, mixed> $data
     */
    public function fromArray(array $data): CreateInvoiceItemData
    {
        return new CreateInvoiceItemData(
            name: $data['name'],
            quantity: (float) $data['quantity'],
            unitPrice: (float) $data['unit_price'],
            unit: $data['unit'],
            vatTypeId: $data['vat_type_id'] === null ? null : (int) $data['vat_type_id'],
        );
    }
}
