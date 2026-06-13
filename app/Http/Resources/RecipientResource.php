<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[LiteralTypeScriptType([
    'id'           => 'number',
    'name'         => 'string | null',
    'company_name' => 'string | null',
    'street'       => 'string | null',
    'street_num'   => 'string | null',
    'city'         => 'string | null',
    'zip'          => 'string | null',
    'state'        => 'string | null',
    'ico'          => 'string | null',
    'dic'          => 'string | null',
    'ic_dph'       => 'string | null',
    'iban'         => 'string | null',
])]
class RecipientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'company_name' => $this->company_name,
            'street'       => $this->street,
            'street_num'   => $this->street_num,
            'city'         => $this->city,
            'zip'          => $this->zip,
            'state'        => $this->state,
            'ico'          => $this->ico,
            'dic'          => $this->dic,
            'ic_dph'       => $this->ic_dph,
            'iban'         => $this->iban,
        ];
    }
}
