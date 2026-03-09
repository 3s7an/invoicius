<?php

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

    public function test_user_can_update_recipient(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->put(route('recipients.update', $recipient), [
                'name' => 'Updated Name',
                'company_name' => 'Updated Corp',
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
            ]);

        $response->assertForbidden();
    }
}
