<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\AutomatizationType;
use App\Enums\InvoiceStatusCode;
use App\Models\Automatization;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\InvoiceStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceStatusAutoUpdateProcessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CurrencySeeder::class);
        $this->seed(InvoiceStatusSeeder::class);

        config([
            'services.n8n.header_name' => 'X-N8N-Token',
            'services.n8n.token' => 'secret-token',
        ]);
    }

    public function test_process_endpoint_flips_overdue_sent_invoices_and_reports_no_email_type(): void
    {
        $user = User::factory()->create();
        $currency = Currency::first();
        $sentStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Sent);

        $invoice = Invoice::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'invoice_status_id' => $sentStatus->id,
            'due_date' => now()->subDay(),
        ]);

        $automatization = Automatization::factory()->create([
            'user_id' => $user->id,
            'type' => AutomatizationType::InvoiceStatusAutoUpdate,
            'date_trigger' => now(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/automatizations/process', [], [
            'X-N8N-Token' => 'secret-token',
        ]);

        $response->assertOk();
        $response->assertJson(['processed' => 1]);

        $result = $response->json('results.0');
        $this->assertTrue($result['success']);
        $this->assertSame(AutomatizationType::InvoiceStatusAutoUpdate->value, $result['type']);
        $this->assertSame(1, $result['data']['updated_count']);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'invoice_status_id' => InvoiceStatus::getByCode(InvoiceStatusCode::Overdue)->id,
        ]);

        $this->assertSame(
            now()->addDay()->toDateString(),
            $automatization->fresh()->date_trigger->toDateString(),
        );
    }
}
