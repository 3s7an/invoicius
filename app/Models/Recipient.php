<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string|null $company_name
 * @property string|null $street
 * @property string|null $street_num
 * @property string|null $city
 * @property string|null $zip
 * @property string|null $state
 * @property-read User|null $user
 */
class Recipient extends Model
{
    /** @use HasFactory<\Database\Factories\RecipientFactory> */
    use HasFactory, SoftDeletes;

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    protected $fillable = [
        'user_id',
        'name',
        'company_name',
        'street',
        'street_num',
        'city',
        'zip',
        'state',
        'ico',
        'dic',
        'ic_dph',
        'iban',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Invoice, $this>
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Automatization, $this>
     */
    public function automatizations(): HasMany
    {
        return $this->hasMany(Automatization::class);
    }
}
