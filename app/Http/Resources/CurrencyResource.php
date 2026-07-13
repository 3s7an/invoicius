<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/** @mixin \App\Models\Currency */
#[TypeScript]
#[LiteralTypeScriptType([
    'id'     => 'number',
    'name'   => 'string',
    'symbol' => 'string',
])]
class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'symbol' => $this->symbol,
        ];
    }
}
