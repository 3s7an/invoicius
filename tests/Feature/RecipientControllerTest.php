<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Recipient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipientControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_recipients(): void
    {
        $response = $this->get(route('recipients.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_recipients(): void
    {
        $response = $this->actingAs($this->user)->get(route('recipients.index'));
        $response->assertOk();
    }

    public function test_user_can_view_create_recipient_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('recipients.create'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Recipients/Create')
            ->where('from_invoice', false));
    }

    public function test_user_can_create_recipient(): void
    {
        $response = $this->actingAs($this->user)->post(route('recipients.store'), [
            'name' => 'John Doe',
            'company_name' => 'ACME Corp',
            'street' => 'Main Street',
            'street_num' => '42',
            'city' => 'Bratislava',
            'zip' => '81101',
            'state' => 'SK',
            'ico' => '12345678',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('recipients', [
            'name' => 'John Doe',
            'company_name' => 'ACME Corp',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_store_from_invoice_redirects_to_invoice_create(): void
    {
        $response = $this->actingAs($this->user)->post(route('recipients.store'), [
            'from_invoice' => '1',
            'name' => 'Invoice Client',
            'company_name' => 'Client Corp',
            'street' => 'Main Street',
            'street_num' => '42',
            'city' => 'Bratislava',
            'zip' => '81101',
            'state' => 'SK',
        ]);

        $recipient = Recipient::query()->where('user_id', $this->user->id)->first();

        $response->assertRedirect(route('invoices.create', ['created_recipient_id' => $recipient->id]));
    }

    public function test_store_rejects_missing_required_address_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('recipients.store'), [
            'name' => 'John Doe',
        ]);

        $response->assertSessionHasErrors(['street', 'street_num', 'city', 'zip', 'state']);
    }

    public function test_user_can_edit_own_recipient(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('recipients.edit', $recipient));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Recipients/Index'));
    }

    public function test_user_can_update_recipient(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->put(route('recipients.update', $recipient), [
                'name' => 'Updated Name',
                'company_name' => 'Updated Corp',
                'street' => $recipient->street,
                'street_num' => $recipient->street_num,
                'city' => $recipient->city,
                'zip' => $recipient->zip,
                'state' => $recipient->state,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('recipients', [
            'id' => $recipient->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_can_delete_recipient(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('recipients.destroy', $recipient));

        $response->assertRedirect();
        $this->assertSoftDeleted('recipients', ['id' => $recipient->id]);
    }

    public function test_user_cannot_update_other_users_recipient(): void
    {
        $otherUser = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->put(route('recipients.update', $recipient), [
                'name' => 'Hacker',
                'street' => $recipient->street,
                'street_num' => $recipient->street_num,
                'city' => $recipient->city,
                'zip' => $recipient->zip,
                'state' => $recipient->state,
            ]);

        $response->assertForbidden();
    }

    public function test_user_cannot_edit_other_users_recipient(): void
    {
        $otherUser = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('recipients.edit', $recipient));

        $response->assertForbidden();
    }

    public function test_user_cannot_delete_other_users_recipient(): void
    {
        $otherUser = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->delete(route('recipients.destroy', $recipient));

        $response->assertForbidden();
    }
}
