<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AutomatizationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $recipient_id
 * @property AutomatizationType $type
 * @property-read string $type_label
 * @property Carbon|null $date_trigger
 * @property int|null $due_offset_days
 * @property bool $is_active
 * @property Carbon|null $last_run_at
 * @property array<string, mixed>|null $result_data
 * @property list<string>|null $item_names
 * @property-read User|null $user
 * @property-read Recipient|null $recipient
 */
class Automatization extends Model
{
    /** @use HasFactory<\Database\Factories\AutomatizationFactory> */
    use HasFactory, SoftDeletes;

    protected $appends = [
        'type_label',
    ];

    protected $fillable = [
        'user_id',
        'recipient_id',
        'type',
        'date_trigger',
        'due_offset_days',
        'is_active',
        'last_run_at',
        'result_data',
        'item_names',
    ];

    protected function casts(): array
    {
        return [
            'type' => AutomatizationType::class,
            'date_trigger' => 'date',
            'due_offset_days' => 'integer',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
            'result_data' => 'array',
            'item_names' => 'array',
        ];
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    protected function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $attributes = parent::toArray();

        if ($this->date_trigger !== null) {
            $attributes['date_trigger'] = $this->date_trigger->toDateString();
        }

        return $attributes;
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function dueToday(Builder $query): Builder
    {
        return $query->whereDate('date_trigger', now()->toDateString())
            ->where('is_active', true);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Recipient, $this> */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }
}
