<?php

namespace App\Models;

use App\Enums\AutomatizationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Automatization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'recipient_id',
        'name',
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
            'date_trigger' => 'date:Y-m-d',
            'due_offset_days' => 'integer',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
            'result_data' => 'array',
            'item_names' => 'array',
        ];
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('date_trigger', now()->toDateString())
            ->where('is_active', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }
}
