<?php

namespace Tests\Unit\Services;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\User;
use App\Services\PaymentQrService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentQrServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentQrService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->service = new PaymentQrService;
    }

    public function test_returns_null_without_issuer_iban(): void
    {
        $user = User::factory()->create(['iban' => null]);
        $invoice = Invoice::factory()->create([
            'user_id' => $user->id,
            'total_price' => 100,
        ]);

        $this->assertNull($this->service->generateDataUriForInvoice($invoice, $user));
    }

    public function test_returns_null_with_zero_amount(): void
    {
        $user = User::factory()->create(['iban' => 'SK31 1200 0000 1987 4263 7541']);
        $invoice = Invoice::factory()->create([
            'user_id' => $user->id,
            'total_price' => 0,
        ]);

        $this->assertNull($this->service->generateDataUriForInvoice($invoice, $user));
    }

    public function test_generates_png_data_uri_for_valid_payment(): void
    {
        $user = User::factory()->create([
            'iban' => 'SK31 1200 0000 1987 4263 7541',
            'company_name' => 'Test s.r.o.',
        ]);
        $currency = Currency::where('symbol', '€')->first();
        $invoice = Invoice::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'total_price' => 123.45,
            'varsym' => '2026001',
            'number' => '2026-001',
        ]);

        $dataUri = $this->service->generateDataUriForInvoice($invoice, $user);

        $this->assertNotNull($dataUri);
        $this->assertStringStartsWith('data:image/png;base64,', $dataUri);
    }

    public function test_normalize_iban_strips_spaces(): void
    {
        $this->assertSame(
            'SK3112000000198742637541',
            $this->service->normalizeIban('SK31 1200 0000 1987 4263 7541')
        );
    }
}
