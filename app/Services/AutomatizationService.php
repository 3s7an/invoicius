<?php

namespace App\Services;

use App\DTOs\AutomatizationDTO;
use App\Models\Automatization;
use Illuminate\Support\Collection;

class AutomatizationService
{
    public function listForUser(int $userId): Collection
    {
        return Automatization::forUser($userId)
            ->with('recipient')
            ->orderByDesc('created_at')
            ->get();
    }

    public function store(AutomatizationDTO $data): Automatization
    {
        return Automatization::create([
            'user_id' => $data->userId,
            'recipient_id' => $data->recipientId,
            'name' => $data->name,
            'type' => $data->type,
            'date_trigger' => $data->dateTrigger,
            'due_offset_days' => $data->dueOffsetDays,
            'item_names' => $data->itemNames,
            'is_active' => $data->isActive,
        ]);
    }

    public function update(Automatization $automatization, AutomatizationDTO $data): Automatization
    {
        $automatization->update([
            'recipient_id' => $data->recipientId,
            'name' => $data->name,
            'type' => $data->type,
            'date_trigger' => $data->dateTrigger,
            'due_offset_days' => $data->dueOffsetDays,
            'item_names' => $data->itemNames,
            'is_active' => $data->isActive,
        ]);

        return $automatization;
    }

    public function delete(Automatization $automatization): void
    {
        $automatization->delete();
    }
}
