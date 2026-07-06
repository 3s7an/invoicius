<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\AutomatizationType;
use App\Enums\InvoiceStatusCode;
use App\Models\Automatization;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\User;
use App\Services\InvoiceStatusAutoUpdateService;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\InvoiceStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceStatusAutoUpdateServiceTest extends TestCase
{
    use RefreshDatabase;

    private InvoiceStatusAutoUpdateService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CurrencySeeder::class);
        $this->seed(InvoiceStatusSeeder::class);

        $this->user = User::factory()->create();
        $this->service = $this->app->make(InvoiceStatusAutoUpdateService::class);
    }

    public function test_moves_sent_invoices_past_due_date_to_overdue(): void
    {
        $currency = Currency::first();
        $sentStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Sent);

        $overdueInvoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency->id,
            'invoice_status_id' => $sentStatus->id,
            'due_date' => now()->subDay(),
        ]);

        $automatization = Automatization::factory()->create([
            'user_id' => $this->user->id,
            'type' => AutomatizationType::InvoiceStatusAutoUpdate,
        ]);

        $result = $this->service->updateOverdueInvoices($automatization);

        $this->assertSame(1, $result['updated_count']);
        $this->assertDatabaseHas('invoices', [
            'id' => $overdueInvoice->id,
            'invoice_status_id' => InvoiceStatus::getByCode(InvoiceStatusCode::Overdue)->id,
        ]);
    }

    public function test_leaves_draft_paid_and_not_yet_due_invoices_untouched(): void
    {
        $currency = Currency::first();
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Draft);
        $paidStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Paid);
        $sentStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Sent);

        $draftInvoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency->id,
            'invoice_status_id' => $draftStatus->id,
            'due_date' => now()->subDay(),
        ]);

        $paidInvoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency->id,
            'invoice_status_id' => $paidStatus->id,
            'due_date' => now()->subDay(),
        ]);

        $notYetDueInvoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency->id,
            'invoice_status_id' => $sentStatus->id,
            'due_date' => now()->addDay(),
        ]);

        $automatization = Automatization::factory()->create([
            'user_id' => $this->user->id,
            'type' => AutomatizationType::InvoiceStatusAutoUpdate,
        ]);

        $result = $this->service->updateOverdueInvoices($automatization);

        $this->assertSame(0, $result['updated_count']);
        $this->assertDatabaseHas('invoices', ['id' => $draftInvoice->id, 'invoice_status_id' => $draftStatus->id]);
        $this->assertDatabaseHas('invoices', ['id' => $paidInvoice->id, 'invoice_status_id' => $paidStatus->id]);
        $this->assertDatabaseHas('invoices', ['id' => $notYetDueInvoice->id, 'invoice_status_id' => $sentStatus->id]);
    }

    public function test_only_updates_invoices_belonging_to_the_automatizations_user(): void
    {
        $currency = Currency::first();
        $sentStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Sent);
        $otherUser = User::factory()->create();

        $otherUsersInvoice = Invoice::factory()->create([
            'user_id' => $otherUser->id,
            'currency_id' => $currency->id,
            'invoice_status_id' => $sentStatus->id,
            'due_date' => now()->subDay(),
        ]);

        $automatization = Automatization::factory()->create([
            'user_id' => $this->user->id,
            'type' => AutomatizationType::InvoiceStatusAutoUpdate,
        ]);

        $result = $this->service->updateOverdueInvoices($automatization);

        $this->assertSame(0, $result['updated_count']);
        $this->assertDatabaseHas('invoices', ['id' => $otherUsersInvoice->id, 'invoice_status_id' => $sentStatus->id]);
    }
}
