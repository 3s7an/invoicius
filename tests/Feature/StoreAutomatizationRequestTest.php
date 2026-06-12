<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AutomatizationType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAutomatizationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_rejects_invalid_automatization_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automatizations.store'), [
            'name' => 'Test',
            'type' => 'foobar',
            'date_trigger' => now()->addDay()->toDateString(),
        ]);

        $response->assertSessionHasErrors(['type']);
    }

    public function test_store_accepts_valid_automatization_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automatizations.store'), [
            'name' => 'Mesačný report',
            'type' => AutomatizationType::InvoiceReport->value,
            'date_trigger' => now()->addDay()->toDateString(),
        ]);

        $response->assertRedirect(route('automatizations.index'));
        $this->assertDatabaseHas('automatizations', [
            'user_id' => $user->id,
            'type' => AutomatizationType::InvoiceReport->value,
        ]);
    }

    public function test_store_rejects_past_date_trigger(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automatizations.store'), [
            'name' => 'Test',
            'type' => AutomatizationType::InvoiceReport->value,
            'date_trigger' => now()->subDay()->toDateString(),
        ]);

        $response->assertSessionHasErrors(['date_trigger']);
    }

    public function test_store_requires_due_offset_days_for_reminder_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automatizations.store'), [
            'name' => 'Reminder',
            'type' => AutomatizationType::InvoiceDueReminder->value,
            'date_trigger' => now()->addDay()->toDateString(),
        ]);

        $response->assertSessionHasErrors(['due_offset_days']);
    }

    public function test_store_requires_recipient_and_item_names_for_auto_gen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('automatizations.store'), [
            'name' => 'Auto',
            'type' => AutomatizationType::InvoiceAutoGen->value,
            'date_trigger' => now()->addDay()->toDateString(),
        ]);

        $response->assertSessionHasErrors(['recipient_id', 'item_names']);
    }
}
