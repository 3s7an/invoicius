<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\Recipient;
use App\Models\User;
use App\Policies\RecipientPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipientPolicyTest extends TestCase
{
    use RefreshDatabase;

    private RecipientPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new RecipientPolicy;
    }

    public function test_owner_can_view_update_and_delete(): void
    {
        $user = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->view($user, $recipient));
        $this->assertTrue($this->policy->update($user, $recipient));
        $this->assertTrue($this->policy->delete($user, $recipient));
    }

    public function test_other_user_cannot_view_update_or_delete(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $recipient = Recipient::factory()->create(['user_id' => $owner->id]);

        $this->assertFalse($this->policy->view($other, $recipient));
        $this->assertFalse($this->policy->update($other, $recipient));
        $this->assertFalse($this->policy->delete($other, $recipient));
    }
}
