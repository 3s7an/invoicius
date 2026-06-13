<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\User;
use App\Services\InvoiceStatsCalculator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceStatsCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private InvoiceStatsCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\InvoiceStatusSeeder::class);

        $this->calculator = $this->app->make(InvoiceStatsCalculator::class);
    }

    public function test_handles_empty_collection(): void
    {
        $stats = $this->calculator->calculateForCollection(collect(), \Illuminate\Support\Facades\Date::today());

        $this->assertSame([
            'total_invoiced' => 0.0,
            'paid' => 0.0,
            'awaiting' => 0.0,
            'overdue' => 0.0,
            'draft' => 0.0,
        ], $stats);
    }

    public function test_calculates_paid_awaiting_overdue_and_draft_totals(): void
    {
        $user = User::factory()->create();
        $currency = Currency::first();
        $draft = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);
        $paid = InvoiceStatus::getByCode(InvoiceStatus::CODE_PAID);
        $sent = InvoiceStatus::getByCode(InvoiceStatus::CODE_SENT);

        $invoices = collect([
            Invoice::factory()->create([
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'invoice_status_id' => $draft->id,
                'total_price' => 100.00,
                'due_date' => now()->addDays(10),
            ]),
            Invoice::factory()->create([
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'invoice_status_id' => $paid->id,
                'total_price' => 200.00,
                'due_date' => now()->subDays(5),
            ]),
            Invoice::factory()->create([
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'invoice_status_id' => $sent->id,
                'total_price' => 150.00,
                'due_date' => now()->addDays(5),
            ]),
            Invoice::factory()->create([
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'invoice_status_id' => $sent->id,
                'total_price' => 50.00,
                'due_date' => now()->subDays(3),
            ]),
        ])->each->load('invoiceStatus');

        $stats = $this->calculator->calculateForCollection($invoices, \Illuminate\Support\Facades\Date::today());

        $this->assertSame(500.0, $stats['total_invoiced']);
        $this->assertSame(200.0, $stats['paid']);
        $this->assertSame(100.0, $stats['draft']);
        $this->assertSame(50.0, $stats['overdue']);
        $this->assertSame(150.0, $stats['awaiting']);
    }
}
