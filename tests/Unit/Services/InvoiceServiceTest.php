<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTOs\InvoiceDTO;
use App\DTOs\InvoiceItemDTO;
use App\DTOs\InvoiceRecipientDTO;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Recipient;
use App\Models\User;
use App\Models\VatType;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    private InvoiceService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\InvoiceStatusSeeder::class);
        $this->seed(\Database\Seeders\VatTypeSeeder::class);

        $this->user = User::factory()->create();
        $this->service = $this->app->make(InvoiceService::class);
    }

    public function test_suggested_number_returns_date_format(): void
    {
        $number = $this->service->getSuggestedNumber($this->user->id);
        $this->assertMatchesRegularExpression('/^\d{8}$/', $number);
    }

    public function test_suggested_number_increments_on_duplicate(): void
    {
        $today = now()->format('Ymd');
        $currency = Currency::first();
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);

        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'number' => $today,
            'currency_id' => $currency->id,
            'invoice_status_id' => $draftStatus->id,
        ]);

        $number = $this->service->getSuggestedNumber($this->user->id);
        $this->assertEquals($today . '-2', $number);
    }

    public function test_create_invoice_calculates_vat_correctly(): void
    {
        $currency = Currency::first();
        $vat23 = VatType::where('code', '23')->first();

        $data = new InvoiceDTO(
            userId: $this->user->id,
            number: 'TEST-001',
            variableSymbol: 'VS-001',
            issueDate: now(),
            dueDate: now()->addDays(14),
            currencyId: $currency->id,
            recipientId: null,
            recipient: new InvoiceRecipientDTO(
                recipientName: 'Test Recipient',
                recipientStreet: 'Test Street',
                recipientStreetNum: '1',
                recipientCity: 'Test City',
                recipientState: 'SK',
                recipientIco: null,
                recipientDic: null,
                recipientIcDph: null,
                recipientIban: null,
            ),
            items: [
                new InvoiceItemDTO(
                    name: 'Service A',
                    quantity: 2.0,
                    unitPrice: 100.00,
                    unit: 'hrs',
                    vatTypeId: $vat23->id,
                ),
            ],
        );

        $invoice = $this->service->createInvoice($data);

        $this->assertEquals(200.00, (float) $invoice->wo_vat_price);
        $this->assertEquals(46.00, (float) $invoice->vat_price);
        $this->assertEquals(246.00, (float) $invoice->total_price);
        $this->assertCount(1, $invoice->items);
    }

    public function test_create_invoice_with_exempt_vat(): void
    {
        $currency = Currency::first();
        $mimo = VatType::where('code', 'MIMO')->first();

        $data = new InvoiceDTO(
            userId: $this->user->id,
            number: 'TEST-002',
            variableSymbol: 'VS-002',
            issueDate: now(),
            dueDate: now()->addDays(14),
            currencyId: $currency->id,
            recipientId: null,
            recipient: new InvoiceRecipientDTO(
                recipientName: 'Test',
                recipientStreet: null,
                recipientStreetNum: null,
                recipientCity: null,
                recipientState: null,
                recipientIco: null,
                recipientDic: null,
                recipientIcDph: null,
                recipientIban: null,
            ),
            items: [
                new InvoiceItemDTO(
                    name: 'Item',
                    quantity: 1.0,
                    unitPrice: 50.00,
                    unit: 'pcs',
                    vatTypeId: $mimo->id,
                ),
            ],
        );

        $invoice = $this->service->createInvoice($data);

        $this->assertEquals(50.00, (float) $invoice->wo_vat_price);
        $this->assertEquals(0.00, (float) $invoice->vat_price);
        $this->assertEquals(50.00, (float) $invoice->total_price);
    }

    public function test_stats_exclude_draft_from_awaiting(): void
    {
        $currency = Currency::first();
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);
        $sentStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_SENT);

        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'number' => 'DRAFT-1',
            'total_price' => 100.00,
            'invoice_status_id' => $draftStatus->id,
            'due_date' => now()->addDays(30),
            'currency_id' => $currency->id,
        ]);

        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'number' => 'SENT-1',
            'total_price' => 200.00,
            'invoice_status_id' => $sentStatus->id,
            'due_date' => now()->addDays(30),
            'currency_id' => $currency->id,
        ]);

        $stats = $this->service->getInvoiceStats($this->user->id);

        $this->assertEquals(300.00, $stats['total_invoiced']);
        $this->assertEquals(200.00, $stats['awaiting']);
        $this->assertEquals(0.00, $stats['paid']);
        $this->assertEquals(100.00, $stats['draft']);
    }

    public function test_create_invoice_rejects_other_users_recipient(): void
    {
        $currency = Currency::first();
        $otherUser = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $otherUser->id]);

        $data = new InvoiceDTO(
            userId: $this->user->id,
            number: 'TEST-003',
            variableSymbol: 'VS-003',
            issueDate: now(),
            dueDate: now()->addDays(14),
            currencyId: $currency->id,
            recipientId: $recipient->id,
            recipient: new InvoiceRecipientDTO(
                recipientName: 'Test',
                recipientStreet: null,
                recipientStreetNum: null,
                recipientCity: null,
                recipientState: null,
                recipientIco: null,
                recipientDic: null,
                recipientIcDph: null,
                recipientIban: null,
            ),
            items: [
                new InvoiceItemDTO(
                    name: 'Item',
                    quantity: 1.0,
                    unitPrice: 100.00,
                    unit: 'pcs',
                    vatTypeId: null,
                ),
            ],
        );

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->service->createInvoice($data);
    }
}
