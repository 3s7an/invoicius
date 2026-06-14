<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[LiteralTypeScriptType([
    'id'          => 'number',
    'name'        => 'string',
    'unit'        => 'string',
    'quantity'    => 'number',
    'unit_price'  => 'number',
    'vat_type_id' => 'number | null',
    'line_total'  => 'number',
])]
class InvoiceItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'unit'        => $this->unit,
            'quantity'    => (float) $this->quantity,
            'unit_price'  => (float) $this->unit_price,
            'vat_type_id' => $this->vat_type_id,
            'line_total'  => (float) $this->line_total,
        ];
    }
}
