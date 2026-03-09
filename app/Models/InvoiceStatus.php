<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceStatus extends Model
{
    use HasFactory;

    public const CODE_DRAFT = 'draft';
    public const CODE_SENT = 'sent';
    public const CODE_PAID = 'paid';
    public const CODE_OVERDUE = 'overdue';

    protected $fillable = [
        'code',
        'name',
    ];

    public static function getByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoice_status_id');
    }
}
