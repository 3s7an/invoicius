<?php

namespace App\DTOs;

use Carbon\Carbon;

final readonly class CreateAutomatizationData
{
    public function __construct(
        public int $userId,
        public ?int $recipientId,
        public string $name,
        public string $type,
        public Carbon $dateTrigger,
        public ?int $dueOffsetDays,
        public ?array $itemNames,
    ) {
    }
}
