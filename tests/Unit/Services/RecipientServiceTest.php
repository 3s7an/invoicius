<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Recipient;
use App\Models\User;
use App\Services\RecipientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RecipientServiceTest extends TestCase
{
    use RefreshDatabase;

    private RecipientService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(RecipientService::class);
        $this->user = User::factory()->create();
    }

    public function test_list_for_user_orders_by_company_name_then_name(): void
    {
        Recipient::factory()->create([
            'user_id' => $this->user->id,
            'company_name' => 'Zebra Corp',
            'name' => 'Alice',
        ]);
        Recipient::factory()->create([
            'user_id' => $this->user->id,
            'company_name' => 'Alpha Corp',
            'name' => 'Bob',
        ]);

        $recipients = $this->service->listForUser($this->user->id);

        $this->assertSame('Alpha Corp', $recipients->first()->company_name);
    }

    public function test_create_for_user_sets_user_id(): void
    {
        $recipient = $this->service->createForUser($this->user->id, [
            'name' => 'John Doe',
            'company_name' => 'ACME',
            'street' => 'Main',
            'street_num' => '1',
            'city' => 'Bratislava',
            'zip' => '81101',
            'state' => 'SK',
        ]);

        $this->assertSame($this->user->id, $recipient->user_id);
        $this->assertDatabaseHas('recipients', [
            'id' => $recipient->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_find_for_user_or_fail_returns_recipient_for_owner(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $found = $this->service->findForUserOrFail($this->user->id, $recipient->id);

        $this->assertTrue($found->is($recipient));
    }

    public function test_find_for_user_or_fail_throws_for_other_users_recipient(): void
    {
        $otherUser = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $otherUser->id]);

        $this->expectException(ValidationException::class);

        $this->service->findForUserOrFail($this->user->id, $recipient->id);
    }

    public function test_update_persists_changes(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $updated = $this->service->update($recipient, ['name' => 'Updated Name']);

        $this->assertSame('Updated Name', $updated->name);
        $this->assertDatabaseHas('recipients', [
            'id' => $recipient->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_delete_soft_deletes_recipient(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $this->service->delete($recipient);

        $this->assertSoftDeleted('recipients', ['id' => $recipient->id]);
    }
}
