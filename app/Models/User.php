<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'password',
        'street',
        'street_num',
        'city',
        'zip',
        'state',
        'ico',
        'dic',
        'ic_dph',
        'iban',
        'currency_id',
        'company_logo_id',
        'invoice_color_id',
        'default_vat_type_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function companyLogo(): BelongsTo
    {
        return $this->belongsTo(UserCompanyLogo::class, 'company_logo_id');
    }

    public function invoiceColor(): BelongsTo
    {
        return $this->belongsTo(InvoiceColor::class, 'invoice_color_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(Recipient::class);
    }

    public function defaultVatType(): BelongsTo
    {
        return $this->belongsTo(VatType::class, 'default_vat_type_id');
    }

    public function automatizations(): HasMany
    {
        return $this->hasMany(Automatization::class);
    }
}
