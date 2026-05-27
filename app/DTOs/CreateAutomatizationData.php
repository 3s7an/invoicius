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
    ) {
    }

    /**
     * @param array<string, mixed> $validated
     */
    public static function fromValidated(array $validated, int $userId): self
    {
        $recipientId = $validated['recipient_id'] ?? null;

        return new self(
            userId: $userId,
            recipientId: ($recipientId === null || $recipientId === '' || (int) $recipientId === 0)
                ? null
                : (int) $recipientId,
            type: $validated['type'],
            dateTrigger: Carbon::parse($validated['date_trigger']),
        );
    }
}
