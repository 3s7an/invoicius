<?php

namespace App\Contracts;

use App\DTOs\CreateAutomatizationData;
use App\Models\Automatization;
use Illuminate\Support\Collection;

interface AutomatizationServiceInterface
{
    public function processDueAutomatizations(): array;

    public function listForUser(int $userId): Collection;

    public function store(CreateAutomatizationData $data): Automatization;

    public function update(Automatization $automatization, array $validated): Automatization;

    public function delete(Automatization $automatization): void;
}
