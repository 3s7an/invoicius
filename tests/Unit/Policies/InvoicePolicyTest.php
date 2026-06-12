<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\User;
use App\Policies\InvoicePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePolicyTest extends TestCase
{
    use RefreshDatabase;

    private InvoicePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\InvoiceStatusSeeder::class);

        $this->policy = new InvoicePolicy;
    }

    public function test_owner_can_view_update_and_delete(): void
    {
        $user = User::factory()->create();
        $invoice = $this->makeInvoice($user);

        $this->assertTrue($this->policy->view($user, $invoice));
        $this->assertTrue($this->policy->update($user, $invoice));
        $this->assertTrue($this->policy->delete($user, $invoice));
    }

    public function test_other_user_cannot_view_update_or_delete(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $invoice = $this->makeInvoice($owner);

        $this->assertFalse($this->policy->view($other, $invoice));
        $this->assertFalse($this->policy->update($other, $invoice));
        $this->assertFalse($this->policy->delete($other, $invoice));
    }

    private function makeInvoice(User $user): Invoice
    {
        return Invoice::factory()->create([
            'user_id' => $user->id,
            'currency_id' => Currency::first()->id,
            'invoice_status_id' => InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT)->id,
        ]);
    }
}
