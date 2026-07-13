<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/** @mixin \App\Models\VatType */
#[TypeScript]
#[LiteralTypeScriptType([
    'id'   => 'number',
    'code' => 'string',
    'rate' => 'number',
])]
class VatTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'code' => $this->code,
            'rate' => (float) $this->rate,
        ];
    }
}
