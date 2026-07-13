<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/** @mixin \App\Models\Invoice */
#[TypeScript]
#[LiteralTypeScriptType([
    'id'                   => 'number',
    'number'               => 'string',
    'variable_symbol'      => 'string | null',
    'recipient_name'       => 'string | null',
    'recipient_street'     => 'string | null',
    'recipient_street_num' => 'string | null',
    'recipient_city'       => 'string | null',
    'recipient_state'      => 'string | null',
    'recipient_ico'        => 'string | null',
    'recipient_dic'        => 'string | null',
    'recipient_ic_dph'     => 'string | null',
    'iban'                 => 'string | null',
    'total_price'          => 'number',
    'issue_date'           => 'string | null',
    'due_date'             => 'string | null',
    'currency_id'          => 'number',
    'recipient_id'         => 'number | null',
    'invoice_status_id'    => 'number',
    'invoice_status'       => 'App.Http.Resources.InvoiceStatusResource | null',
    'items'                => 'App.Http.Resources.InvoiceItemResource[] | null',
    'created_at'           => 'string | null',
])]
class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'number'               => $this->number,
            'variable_symbol'      => $this->varsym,
            'recipient_name'       => $this->recipient_name,
            'recipient_street'     => $this->recipient_street,
            'recipient_street_num' => $this->recipient_street_num,
            'recipient_city'       => $this->recipient_city,
            'recipient_state'      => $this->recipient_state,
            'recipient_ico'        => $this->recipient_ico,
            'recipient_dic'        => $this->recipient_dic,
            'recipient_ic_dph'     => $this->recipient_ic_dph,
            'iban'                 => $this->iban,
            'total_price'          => (float) $this->total_price,
            'issue_date'           => $this->issue_date?->toDateString(),
            'due_date'             => $this->due_date?->toDateString(),
            'currency_id'          => $this->currency_id,
            'recipient_id'         => $this->recipient_id,
            'invoice_status_id'    => $this->invoice_status_id,
            'invoice_status'       => InvoiceStatusResource::make($this->whenLoaded('invoiceStatus')),
            'items'                => InvoiceItemResource::collection($this->whenLoaded('items')),
            'created_at'           => $this->created_at?->toDateString(),
        ];
    }
}
