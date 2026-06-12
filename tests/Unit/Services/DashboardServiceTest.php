<?php 

declare(strict_types=1);

use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardServiceTest extends TestCase {
    use RefreshDatabase;

    private DashboardService $service;
    private User $user;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\InvoiceStatusSeeder::class);

        $this->user = User::factory()->create();
        $this->service = $this->app->make(DashboardService::class);
    }

    public function test_get_counts_returns_zero_for_new_user(): void {
        $counts = $this->service->getCounts($this->user->id);

        $this->assertSame([
            'invoices' => 0,
            'clients' => 0, 
            'automatizations_active' => 0
        ], $counts);
    }

    public function test_get_count_only_includes_current_users_data(): void {
        $otherUser = User::factory()->create();
        $currency = \App\Models\Currency::first();
        $status = \App\Models\InvoiceStatus::first();

        App\Models\Invoice::factory()->create([
            'user_id' => $otherUser->id,
            'currency_id' => $currency->id, 
            'invoice_status_id' => $status->id
        ]);

        App\Models\Recipient::factory()->create([
            'user_id' => $this->user->id
        ]);

        $counts = $this->service->getCounts($this->user->id);

        $this->assertSame(0, $counts['invoices']);
        $this->assertSame(1, $counts['clients']);
    }

    public function test_get_recent_invoices_returns_max_six_ordered_by_created_at(): void
    {
        $currency = \App\Models\Currency::first();
        $status = \App\Models\InvoiceStatus::first();
        for ($i = 1; $i <= 8; $i++) {
            App\Models\Invoice::factory()->create([
                'user_id' => $this->user->id,
                'number' => "INV-{$i}",
                'currency_id' => $currency->id,
                'invoice_status_id' => $status->id,
                'created_at' => now()->subDays(8 - $i),
            ]);
        }
        $recent = $this->service->getRecentInvoices($this->user->id, 6);
        $this->assertCount(6, $recent);
        $this->assertSame('INV-8', $recent->first()['number']); // najnovšia
    }
}