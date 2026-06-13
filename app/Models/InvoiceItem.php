<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'vat_type_id',
        'name',
        'unit',
        'quantity',
        'unit_price',
        'unit_wo_vat',
        'discount',
        'vat',
        'position',
        'line_total',
        'line_wo_vat',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:2',
            'unit_wo_vat' => 'decimal:2',
            'discount' => 'decimal:2',
            'vat' => 'decimal:2',
            'line_total' => 'decimal:2',
            'line_wo_vat' => 'decimal:2',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\VatType, $this>
     */
    public function vatType(): BelongsTo
    {
        return $this->belongsTo(VatType::class, 'vat_type_id');
    }
}
