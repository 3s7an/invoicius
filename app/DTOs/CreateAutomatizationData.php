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
        return new self(
            userId: $userId,
            recipientId: (int) $validated['recipient_id'],
            type: $validated['type'],
            dateTrigger: Carbon::parse($validated['date_trigger']),
        );
    }
}
