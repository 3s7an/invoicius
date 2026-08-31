<?php

declare(strict_types=1);

namespace App\Services;

class IbanService
{
    public function normalizeIban(?string $iban): ?string
    {
        if ($iban === null || trim($iban) === '') {
            return null;
        }

        $normalized = strtoupper(preg_replace('/\s+/', '', $iban) ?? '');

        return preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]+$/', $normalized) === 1
            ? $normalized
            : null;
    }
}
