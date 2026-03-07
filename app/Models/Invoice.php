<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    protected $fillable = [
        'user_id',
        'recipient_id',
        'varsym',
        'number',
        'payment_type',
        'recipient_name',
        'recipient_street',
        'recipient_street_num',
        'recipient_city',
        'recipient_state',
        'recipient_ico',
        'recipient_dic',
        'recipient_ic_dph',
        'issue_date',
        'due_date',
        'iban',
        'total_price',
        'vat_price',
        'wo_vat_price',
        'invoice_status_id',
        'currency_id',
        'notes',
        'sequence_number',
        'year',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'total_price' => 'decimal:2',
            'vat_price' => 'decimal:2',
            'wo_vat_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('position');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function invoiceStatus(): BelongsTo
    {
        return $this->belongsTo(InvoiceStatus::class, 'invoice_status_id');
    }
}
