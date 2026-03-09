<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\InvoiceStatusSeeder::class);
        $this->seed(\Database\Seeders\VatTypeSeeder::class);
        $this->seed(\Database\Seeders\InvoiceColorSeeder::class);

        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_invoices(): void
    {
        $response = $this->get(route('invoices'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_invoices_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('invoices'));
        $response->assertOk();
    }

    public function test_user_can_create_invoice(): void
    {
        $currency = Currency::first();

        $response = $this->actingAs($this->user)->post(route('invoices.store'), [
            'number' => 'INV-001',
            'variable_symbol' => 'VS-001',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'currency_id' => $currency->id,
            'recipient_id' => null,
            'issuer' => ['name' => 'Test Company'],
            'recipient' => [
                'recipient_name' => 'Client Inc.',
                'recipient_street' => '123 Main St',
                'recipient_street_num' => '1',
                'recipient_city' => 'Bratislava',
                'recipient_state' => 'SK',
            ],
            'items' => [
                [
                    'name' => 'Web Development',
                    'quantity' => 10,
                    'unit' => 'hrs',
                    'unit_price' => 50.00,
                    'vat_type_id' => null,
                ],
            ],
        ]);

        $response->assertRedirect(route('invoices'));
        $this->assertDatabaseHas('invoices', [
            'number' => 'INV-001',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_cannot_create_invoice_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->post(route('invoices.store'), [
            'number' => '',
            'items' => [],
        ]);

        $response->assertSessionHasErrors(['number', 'items']);
    }

    public function test_user_can_update_invoice_status(): void
    {
        $currency = Currency::first();
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);
        $paidStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_PAID);

        $invoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'number' => 'INV-STATUS',
            'currency_id' => $currency->id,
            'invoice_status_id' => $draftStatus->id,
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('invoices.update-status', $invoice), [
                'invoice_status_id' => $paidStatus->id,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'invoice_status_id' => $paidStatus->id,
        ]);
    }

    public function test_user_can_delete_invoice(): void
    {
        $currency = Currency::first();
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);

        $invoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'number' => 'INV-DEL',
            'currency_id' => $currency->id,
            'invoice_status_id' => $draftStatus->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('invoices.destroy', $invoice));

        $response->assertRedirect();
        $this->assertSoftDeleted('invoices', ['id' => $invoice->id]);
    }

    public function test_user_cannot_access_other_users_invoice(): void
    {
        $otherUser = User::factory()->create();
        $currency = Currency::first();
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);

        $invoice = Invoice::factory()->create([
            'user_id' => $otherUser->id,
            'number' => 'OTHER-001',
            'currency_id' => $currency->id,
            'invoice_status_id' => $draftStatus->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('invoices.destroy', $invoice));

        $response->assertForbidden();
    }
}
