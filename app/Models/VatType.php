<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VatType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'rate',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\InvoiceItem, $this>
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'vat_type_id');
    }
}
