<?php

namespace App\DTOs;

use App\Enums\AutomatizationType;
use Carbon\Carbon;

final readonly class AutomatizationDTO
{
    public function __construct(
        public int $userId,
        public ?int $recipientId,
        public string $name,
        public AutomatizationType $type,
        public Carbon $dateTrigger,
        public ?int $dueOffsetDays,
        public ?array $itemNames,
        public bool $isActive,
    ) {
    }

    /**
     * @param array<string, mixed> $validated
     */
    public static function fromValidated(array $validated, int $userId): self
    {
        $recipientId = $validated['recipient_id'] ?? null;
        $dueOffsetDays = $validated['due_offset_days'] ?? null;
        $type = AutomatizationType::from((string) $validated['type']);

        return new self(
            userId: $userId,
            recipientId: ($recipientId === null || $recipientId === '' || (int) $recipientId === 0)
                ? null
                : (int) $recipientId,
            name: (string) $validated['name'],
            type: $type,
            dateTrigger: $type->usesDailySchedule()
                ? now()->startOfDay()
                : Carbon::parse($validated['date_trigger']),
            dueOffsetDays: ($dueOffsetDays === null || $dueOffsetDays === '' ? null : (int) $dueOffsetDays),
            itemNames: $type->requiresItemNames()
                ? array_values($validated['item_names'] ?? [])
                : null,
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }
}
