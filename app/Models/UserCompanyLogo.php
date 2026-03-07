<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserCompanyLogo extends Model
{
    protected $table = 'users_companies_logo';

    protected $fillable = [
        'user_id',
        'link',
        'file_name',
    ];

    protected $appends = ['url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return '/storage/' . ltrim($this->link, '/');
    }
}
