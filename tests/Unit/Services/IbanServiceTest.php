<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\IbanService;
use Tests\TestCase;

class IbanServiceTest extends TestCase
{
    private IbanService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new IbanService;
    }

    public function test_normalize_iban_strips_spaces(): void
    {
        $this->assertSame(
            'SK3112000000198742637541',
            $this->service->normalizeIban('SK31 1200 0000 1987 4263 7541')
        );
    }

    public function test_normalize_iban_returns_null_for_blank_input(): void
    {
        $this->assertNull($this->service->normalizeIban(null));
        $this->assertNull($this->service->normalizeIban('   '));
    }

    public function test_normalize_iban_returns_null_for_invalid_format(): void
    {
        $this->assertNull($this->service->normalizeIban('not-an-iban'));
    }
}
