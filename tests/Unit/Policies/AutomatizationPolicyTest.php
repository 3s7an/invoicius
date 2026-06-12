<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\Automatization;
use App\Models\User;
use App\Policies\AutomatizationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomatizationPolicyTest extends TestCase
{
    use RefreshDatabase;

    private AutomatizationPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new AutomatizationPolicy;
    }

    public function test_owner_can_view_update_and_delete(): void
    {
        $user = User::factory()->create();
        $automatization = Automatization::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->view($user, $automatization));
        $this->assertTrue($this->policy->update($user, $automatization));
        $this->assertTrue($this->policy->delete($user, $automatization));
    }

    public function test_other_user_cannot_view_update_or_delete(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $automatization = Automatization::factory()->create(['user_id' => $owner->id]);

        $this->assertFalse($this->policy->view($other, $automatization));
        $this->assertFalse($this->policy->update($other, $automatization));
        $this->assertFalse($this->policy->delete($other, $automatization));
    }
}
