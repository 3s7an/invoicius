<?php

namespace App\Contracts;

use App\DTOs\AutomatizationResultDTO;
use App\Models\Automatization;

interface AutomatizationHandlerInterface
{
    public function type(): string;

    public function handle(Automatization $automatization): AutomatizationResultDTO;
}
