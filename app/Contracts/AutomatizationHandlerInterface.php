<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\AutomatizationResultDTO;
use App\Enums\AutomatizationType;
use App\Models\Automatization;

interface AutomatizationHandlerInterface
{
    public function type(): AutomatizationType;

    public function handle(Automatization $automatization): AutomatizationResultDTO;
}
