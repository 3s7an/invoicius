<?php 

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase {
    use RefreshDatabase;

    public function test_authentificated_user_can_view_dashboard(): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_guest_is_redirected_to_login(): void {

        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));

    }

    public function test_dashboard_returns_invoice_stats_for_authenticated_user(): void {
        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\InvoiceStatusSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard/Dashboard')
            ->has('invoice_stats')
            ->has('counts')
            ->has('recent_invoices')
            ->has('active_automatizations')
        );
    }
}

