<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Models\Recipient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomatizationControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_view_automatizations_index(): void
    {
        $response = $this->get(route('automatizations.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_automatizations_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('automatizations.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Automatizations/Index')
            ->has('automatizations'));
    }

    public function test_user_can_view_create_automatization_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('automatizations.create'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Automatizations/Create')
            ->has('recipients')
            ->has('automatization_types'));
    }

    public function test_user_can_store_invoice_report_automatization(): void
    {
        $response = $this->actingAs($this->user)->post(route('automatizations.store'), [
            'type' => AutomatizationType::InvoiceReport->value,
            'date_trigger' => now()->addDay()->toDateString(),
        ]);

        $response->assertRedirect(route('automatizations.index'));
        $this->assertDatabaseHas('automatizations', [
            'user_id' => $this->user->id,
            'type' => AutomatizationType::InvoiceReport->value,
        ]);
    }

    public function test_user_can_store_invoice_due_reminder_automatization(): void
    {
        $response = $this->actingAs($this->user)->post(route('automatizations.store'), [
            'type' => AutomatizationType::InvoiceDueReminder->value,
            'date_trigger' => now()->addDay()->toDateString(),
            'due_offset_days' => 3,
        ]);

        $response->assertRedirect(route('automatizations.index'));
        $this->assertDatabaseHas('automatizations', [
            'user_id' => $this->user->id,
            'due_offset_days' => 3,
            'type' => AutomatizationType::InvoiceDueReminder->value,
        ]);
    }

    public function test_user_can_store_invoice_auto_gen_automatization(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->post(route('automatizations.store'), [
            'type' => AutomatizationType::InvoiceAutoGen->value,
            'date_trigger' => now()->addDay()->toDateString(),
            'recipient_id' => $recipient->id,
            'item_names' => ['Konzultácie'],
        ]);

        $response->assertRedirect(route('automatizations.index'));
        $this->assertDatabaseHas('automatizations', [
            'user_id' => $this->user->id,
            'recipient_id' => $recipient->id,
            'type' => AutomatizationType::InvoiceAutoGen->value,
        ]);
    }

    public function test_store_rejects_other_users_recipient_id(): void
    {
        $otherUser = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->post(route('automatizations.store'), [
            'type' => AutomatizationType::InvoiceAutoGen->value,
            'date_trigger' => now()->addDay()->toDateString(),
            'recipient_id' => $recipient->id,
            'item_names' => ['Konzultácie'],
        ]);

        $response->assertSessionHasErrors(['recipient_id']);
    }

    public function test_user_can_edit_own_automatization(): void
    {
        $automatization = Automatization::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('automatizations.edit', $automatization));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Automatizations/Edit')
            ->has('automatization')
            ->has('recipients')
            ->has('automatization_types'));
    }

    public function test_user_cannot_edit_other_users_automatization(): void
    {
        $otherUser = User::factory()->create();
        $automatization = Automatization::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('automatizations.edit', $automatization));

        $response->assertForbidden();
    }

    public function test_user_can_update_own_automatization(): void
    {
        $automatization = Automatization::factory()->create([
            'user_id' => $this->user->id,
            'type' => AutomatizationType::InvoiceReport,
        ]);

        $response = $this->actingAs($this->user)->put(route('automatizations.update', $automatization), [
            'type' => AutomatizationType::InvoiceDueReminder->value,
            'date_trigger' => now()->addDays(2)->toDateString(),
            'due_offset_days' => 1,
            'is_active' => false,
        ]);

        $response->assertRedirect(route('automatizations.index'));
        $this->assertDatabaseHas('automatizations', [
            'id' => $automatization->id,
            'type' => AutomatizationType::InvoiceDueReminder->value,
            'is_active' => false,
        ]);
    }

    public function test_user_cannot_update_other_users_automatization(): void
    {
        $otherUser = User::factory()->create();
        $automatization = Automatization::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->put(route('automatizations.update', $automatization), [
            'type' => AutomatizationType::InvoiceReport->value,
            'date_trigger' => now()->addDay()->toDateString(),
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_own_automatization(): void
    {
        $automatization = Automatization::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('automatizations.destroy', $automatization));

        $response->assertRedirect(route('automatizations.index'));
        $this->assertSoftDeleted('automatizations', ['id' => $automatization->id]);
    }

    public function test_user_cannot_delete_other_users_automatization(): void
    {
        $otherUser = User::factory()->create();
        $automatization = Automatization::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->delete(route('automatizations.destroy', $automatization));

        $response->assertForbidden();
    }
}
