<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\InvoiceStatusCode;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\User;
use App\Services\InvoicePdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Spatie\LaravelPdf\PdfBuilder;
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
        $response->assertInertia(fn ($page) => $page
            ->component('Invoices/Index')
            ->has('invoices')
            ->has('invoice_stats')
            ->has('invoice_statuses'));
    }

    public function test_user_can_view_create_invoice_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('invoices.create'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Invoices/Create'));
    }

    public function test_user_can_create_invoice(): void
    {
        $response = $this->actingAs($this->user)->post(
            route('invoices.store'),
            $this->validInvoicePayload(['number' => 'INV-001']),
        );

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

    public function test_user_can_edit_invoice(): void
    {
        $invoice = $this->makeInvoice(['number' => 'INV-EDIT']);

        $response = $this->actingAs($this->user)->get(route('invoices.edit', $invoice));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Invoices/Edit'));
    }

    public function test_user_can_update_invoice(): void
    {
        $invoice = $this->makeInvoice(['number' => 'INV-UPDATE']);

        $response = $this->actingAs($this->user)->put(
            route('invoices.update', $invoice),
            $this->validInvoicePayload([
                'number' => 'INV-UPDATED',
                'variable_symbol' => 'VS-UPDATED',
            ]),
        );

        $response->assertRedirect(route('invoices'));
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'number' => 'INV-UPDATED',
            'varsym' => 'VS-UPDATED',
        ]);
    }

    public function test_user_can_download_invoice_pdf(): void
    {
        $invoice = $this->makeInvoice(['number' => 'INV-PDF']);

        $httpResponse = response('pdf-binary', 200, [
            'Content-Type' => 'application/pdf',
        ]);

        $pdfBuilder = Mockery::mock(PdfBuilder::class);
        $pdfBuilder->shouldReceive('toResponse')
            ->once()
            ->andReturn($httpResponse);

        $this->mock(InvoicePdfService::class, function ($mock) use ($invoice, $pdfBuilder): void {
            $mock->shouldReceive('getPdfDownloadResponse')
                ->once()
                ->withArgs(fn (Invoice $arg): bool => $arg->is($invoice))
                ->andReturn($pdfBuilder);
        });

        $response = $this->actingAs($this->user)->get(route('invoices.pdf', $invoice));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_store_rejects_duplicate_invoice_number(): void
    {
        $this->actingAs($this->user)->post(
            route('invoices.store'),
            $this->validInvoicePayload(['number' => 'INV-DUP']),
        );

        $response = $this->actingAs($this->user)->post(
            route('invoices.store'),
            $this->validInvoicePayload([
                'number' => 'INV-DUP',
                'variable_symbol' => 'VS-DUP-2',
            ]),
        );

        $response->assertSessionHasErrors(['number']);
    }

    public function test_store_rejects_due_date_before_issue_date(): void
    {
        $response = $this->actingAs($this->user)->post(
            route('invoices.store'),
            $this->validInvoicePayload([
                'issue_date' => now()->toDateString(),
                'due_date' => now()->subDay()->toDateString(),
            ]),
        );

        $response->assertSessionHasErrors(['due_date']);
    }

    public function test_user_can_update_invoice_status(): void
    {
        $paidStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Paid);

        $invoice = $this->makeInvoice(['number' => 'INV-STATUS']);

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

    public function test_update_status_rejects_invalid_status_id(): void
    {
        $invoice = $this->makeInvoice(['number' => 'INV-STATUS-ERR']);

        $response = $this->actingAs($this->user)
            ->patch(route('invoices.update-status', $invoice), [
                'invoice_status_id' => 99999,
            ]);

        $response->assertSessionHasErrors(['invoice_status_id']);
    }

    public function test_user_cannot_update_other_users_invoice_status(): void
    {
        $otherUser = User::factory()->create();
        $invoice = $this->makeInvoiceForUser($otherUser, ['number' => 'OTHER-STATUS']);
        $paidStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Paid);

        $response = $this->actingAs($this->user)
            ->patch(route('invoices.update-status', $invoice), [
                'invoice_status_id' => $paidStatus->id,
            ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_invoice(): void
    {
        $invoice = $this->makeInvoice(['number' => 'INV-DEL']);

        $response = $this->actingAs($this->user)
            ->delete(route('invoices.destroy', $invoice));

        $response->assertRedirect();
        $this->assertSoftDeleted('invoices', ['id' => $invoice->id]);
    }

    public function test_user_cannot_access_other_users_invoice(): void
    {
        $otherUser = User::factory()->create();
        $invoice = $this->makeInvoiceForUser($otherUser, ['number' => 'OTHER-001']);

        $response = $this->actingAs($this->user)
            ->delete(route('invoices.destroy', $invoice));

        $response->assertForbidden();
    }

    public function test_user_cannot_edit_other_users_invoice(): void
    {
        $otherUser = User::factory()->create();
        $invoice = $this->makeInvoiceForUser($otherUser, ['number' => 'OTHER-EDIT']);

        $response = $this->actingAs($this->user)->get(route('invoices.edit', $invoice));

        $response->assertForbidden();
    }

    public function test_user_cannot_update_other_users_invoice(): void
    {
        $otherUser = User::factory()->create();
        $invoice = $this->makeInvoiceForUser($otherUser, ['number' => 'OTHER-UPDATE']);

        $response = $this->actingAs($this->user)->put(
            route('invoices.update', $invoice),
            $this->validInvoicePayload(['number' => 'HACKED']),
        );

        $response->assertForbidden();
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'number' => 'OTHER-UPDATE',
        ]);
    }

    public function test_user_cannot_download_other_users_invoice_pdf(): void
    {
        $otherUser = User::factory()->create();
        $invoice = $this->makeInvoiceForUser($otherUser, ['number' => 'OTHER-PDF']);

        $response = $this->actingAs($this->user)->get(route('invoices.pdf', $invoice));

        $response->assertForbidden();
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validInvoicePayload(array $overrides = []): array
    {
        return array_merge([
            'number' => 'INV-001',
            'variable_symbol' => 'VS-001',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'currency_id' => Currency::first()->id,
            'recipient_id' => null,
            'issuer' => ['name' => 'Test Company'],
            'recipient' => [
                'recipient_name' => 'Client Inc.',
                'recipient_street' => '123 Main St',
                'recipient_street_num' => '1',
                'recipient_city' => 'Bratislava',
                'recipient_state' => 'SK',
                'recipient_ico' => null,
                'recipient_dic' => null,
                'recipient_ic_dph' => null,
                'recipient_iban' => null,
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
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function makeInvoice(array $attributes = []): Invoice
    {
        return $this->makeInvoiceForUser($this->user, $attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function makeInvoiceForUser(User $user, array $attributes = []): Invoice
    {
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatusCode::Draft);

        return Invoice::factory()->create(array_merge([
            'user_id' => $user->id,
            'currency_id' => Currency::first()->id,
            'invoice_status_id' => $draftStatus->id,
        ], $attributes));
    }
}
