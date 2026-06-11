<?php

namespace App\Contracts;

use App\DTOs\AutomatizationResultDTO;
use App\Enums\AutomatizationType;
use App\Models\Automatization;

interface AutomatizationHandlerInterface
{
    public function type(): AutomatizationType;

    public function handle(Automatization $automatization): AutomatizationResultDTO;
}
