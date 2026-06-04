<?php

namespace App\Mappers;

use App\DTOs\CreateAutomatizationData;
use Carbon\Carbon;

final class CreateAutomatizationDataMapper
{
    /**
     * @param array<string, mixed> $validated
     */
    public function fromValidated(array $validated, int $userId): CreateAutomatizationData
    {
        $recipientId = $validated['recipient_id'] ?? null;
        $dueOffsetDays = $validated['due_offset_days'] ?? null;
        $type = (string) $validated['type'];

        return new CreateAutomatizationData(
            userId: $userId,
            recipientId: ($recipientId === null || $recipientId === '' || (int) $recipientId === 0)
                ? null
                : (int) $recipientId,
            name: (string) $validated['name'],
            type: $type,
            dateTrigger: $type === 'invoice_due_reminder'
                ? now()->startOfDay()
                : Carbon::parse($validated['date_trigger']),
            dueOffsetDays: ($dueOffsetDays === null || $dueOffsetDays === '' ? null : (int) $dueOffsetDays),
        );
    }
}
