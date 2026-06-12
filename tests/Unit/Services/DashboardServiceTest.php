<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Automatization;
use App\Models\Invoice;
use App\Models\Recipient;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    private DashboardService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\InvoiceStatusSeeder::class);

        $this->user = User::factory()->create();
        $this->service = $this->app->make(DashboardService::class);
    }

    public function test_get_counts_returns_zero_for_new_user(): void
    {
        $counts = $this->service->getCounts($this->user->id);

        $this->assertSame([
            'invoices' => 0,
            'clients' => 0,
            'automatizations_active' => 0,
        ], $counts);
    }

    public function test_get_count_only_includes_current_users_data(): void
    {
        $otherUser = User::factory()->create();
        $currency = \App\Models\Currency::first();
        $status = \App\Models\InvoiceStatus::first();

        Invoice::factory()->create([
            'user_id' => $otherUser->id,
            'currency_id' => $currency->id,
            'invoice_status_id' => $status->id,
        ]);

        Recipient::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $counts = $this->service->getCounts($this->user->id);

        $this->assertSame(0, $counts['invoices']);
        $this->assertSame(1, $counts['clients']);
    }

    public function test_get_counts_includes_active_automatizations(): void
    {
        Automatization::factory()->create([
            'user_id' => $this->user->id,
            'is_active' => true,
        ]);
        Automatization::factory()->inactive()->create([
            'user_id' => $this->user->id,
        ]);

        $counts = $this->service->getCounts($this->user->id);

        $this->assertSame(1, $counts['automatizations_active']);
    }

    public function test_get_recent_invoices_returns_max_six_ordered_by_created_at(): void
    {
        $currency = \App\Models\Currency::first();
        $status = \App\Models\InvoiceStatus::first();

        for ($i = 1; $i <= 8; $i++) {
            Invoice::factory()->create([
                'user_id' => $this->user->id,
                'number' => "INV-{$i}",
                'currency_id' => $currency->id,
                'invoice_status_id' => $status->id,
                'created_at' => now()->subDays(8 - $i),
            ]);
        }

        $recent = $this->service->getRecentInvoices($this->user->id, 6);

        $this->assertCount(6, $recent);
        $this->assertSame('INV-8', $recent->first()['number']);
    }

    public function test_get_active_automatizations_returns_only_active_for_user(): void
    {
        Automatization::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Active',
            'is_active' => true,
        ]);
        Automatization::factory()->inactive()->create([
            'user_id' => $this->user->id,
            'name' => 'Inactive',
        ]);
        Automatization::factory()->create([
            'user_id' => User::factory()->create()->id,
            'is_active' => true,
        ]);

        $active = $this->service->getActiveAutomatizations($this->user->id, 6);

        $this->assertCount(1, $active);
        $this->assertSame('Active', $active->first()['name']);
    }

    public function test_get_active_automatizations_limits_to_six(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Automatization::factory()->create([
                'user_id' => $this->user->id,
                'name' => "Auto {$i}",
                'is_active' => true,
                'updated_at' => now()->subDays(8 - $i),
            ]);
        }

        $active = $this->service->getActiveAutomatizations($this->user->id, 6);

        $this->assertCount(6, $active);
    }

    public function test_get_active_automatizations_includes_recipient_label(): void
    {
        $recipient = Recipient::factory()->create([
            'user_id' => $this->user->id,
            'company_name' => 'ACME s.r.o.',
            'name' => 'John',
        ]);

        Automatization::factory()->create([
            'user_id' => $this->user->id,
            'recipient_id' => $recipient->id,
            'is_active' => true,
        ]);

        $active = $this->service->getActiveAutomatizations($this->user->id, 6);

        $this->assertSame('ACME s.r.o.', $active->first()['recipient_label']);
    }
}
