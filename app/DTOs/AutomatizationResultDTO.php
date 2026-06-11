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

    public static function success(array $data): self
    {
        return new self(success: true, data: $data);
    }
    public static function failure(string $error, array $data = []): self
    {
        return new self(success: false, data: $data, error: $error);
    }
}
