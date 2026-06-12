<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAutomatizationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_invoice_report_accepts_payload_without_item_names(): void
    {
        $user = User::factory()->create();
        $automatization = Automatization::factory()->create([
            'user_id' => $user->id,
            'type' => AutomatizationType::InvoiceReport,
            'date_trigger' => now()->addDays(5)->toDateString(),
        ]);

        $newDate = now()->addDays(3)->toDateString();

        $response = $this->actingAs($user)->put(route('automatizations.update', $automatization), [
            'type' => AutomatizationType::InvoiceReport->value,
            'date_trigger' => $newDate,
            'item_names' => [''],
            'is_active' => true,
        ]);

        $response->assertRedirect(route('automatizations.index'));
        $this->assertDatabaseHas('automatizations', [
            'id' => $automatization->id,
            'date_trigger' => $newDate,
        ]);
    }

    public function test_update_invoice_report_rejects_empty_item_names_when_type_switched_to_auto_gen(): void
    {
        $user = User::factory()->create();
        $automatization = Automatization::factory()->create([
            'user_id' => $user->id,
            'type' => AutomatizationType::InvoiceReport,
        ]);

        $response = $this->actingAs($user)->put(route('automatizations.update', $automatization), [
            'type' => AutomatizationType::InvoiceAutoGen->value,
            'date_trigger' => now()->addDay()->toDateString(),
            'item_names' => [''],
        ]);

        $response->assertSessionHasErrors(['item_names.0', 'recipient_id']);
    }
}
