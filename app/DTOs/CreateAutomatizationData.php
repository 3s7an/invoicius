<?php

namespace App\DTOs;

use Carbon\Carbon;

final readonly class CreateAutomatizationData
{
    public function __construct(
        public int $userId,
        public ?int $recipientId,
        public string $type,
        public Carbon $dateTrigger,
        public ?int $dueOffsetDays,
    ) {
    }

    /**
     * @param array<string, mixed> $validated
     */
    public static function fromValidated(array $validated, int $userId): self
    {
        $recipientId = $validated['recipient_id'] ?? null;
        $dueOffsetDays = $validated['due_offset_days'] ?? null;
        $type = (string) $validated['type'];

        return new self(
            userId: $userId,
            recipientId: ($recipientId === null || $recipientId === '' || (int) $recipientId === 0)
                ? null
                : (int) $recipientId,
            type: $type,
            dateTrigger: $type === 'invoice_due_reminder'
                ? now()->startOfDay()
                : Carbon::parse($validated['date_trigger']),
            dueOffsetDays: ($dueOffsetDays === null || $dueOffsetDays === '' ? null : (int) $dueOffsetDays),
        );
    }
}
