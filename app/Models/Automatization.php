<?php

namespace App\Models;

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
        'type',
        'date_trigger',
        'is_active',
        'last_run_at',
        'result_data',
    ];

    protected function casts(): array
    {
        return [
            'date_trigger' => 'date',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
            'result_data' => 'array',
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
