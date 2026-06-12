<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $symbol
 */
class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
    ];
}
