<?php

namespace App\DTOs;

final readonly class AutomatizationResultDTO
{
    public function __construct(
        public bool $success,
        public array $data = [],
        public ?string $error = null,
    ) {
    }
}
