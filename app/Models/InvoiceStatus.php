<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatusCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 */
class InvoiceStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public static function getByCode(InvoiceStatusCode $code): ?self
    {
        return static::where('code', $code->value)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Invoice, $this>
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoice_status_id');
    }
}
