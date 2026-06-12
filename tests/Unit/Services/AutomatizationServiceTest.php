<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTOs\AutomatizationDTO;
use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Models\Recipient;
use App\Models\User;
use App\Services\AutomatizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomatizationServiceTest extends TestCase
{
    use RefreshDatabase;

    private AutomatizationService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(AutomatizationService::class);
        $this->user = User::factory()->create();
    }

    public function test_list_for_user_returns_only_own_automatizations(): void
    {
        Automatization::factory()->create(['user_id' => $this->user->id]);
        Automatization::factory()->create(['user_id' => User::factory()->create()->id]);

        $list = $this->service->listForUser($this->user->id);

        $this->assertCount(1, $list);
        $this->assertSame($this->user->id, $list->first()->user_id);
    }

    public function test_store_creates_automatization_from_dto(): void
    {
        $recipient = Recipient::factory()->create(['user_id' => $this->user->id]);

        $dto = new AutomatizationDTO(
            userId: $this->user->id,
            recipientId: $recipient->id,
            type: AutomatizationType::InvoiceAutoGen,
            dateTrigger: now()->addDay(),
            dueOffsetDays: null,
            itemNames: ['Service'],
            isActive: true,
        );

        $automatization = $this->service->store($dto);

        $this->assertDatabaseHas('automatizations', [
            'id' => $automatization->id,
            'user_id' => $this->user->id,
            'recipient_id' => $recipient->id,
            'type' => AutomatizationType::InvoiceAutoGen->value,
        ]);
    }

    public function test_update_persists_changed_fields(): void
    {
        $automatization = Automatization::factory()->create([
            'user_id' => $this->user->id,
            'type' => AutomatizationType::InvoiceReport,
            'is_active' => true,
        ]);

        $dto = new AutomatizationDTO(
            userId: $this->user->id,
            recipientId: null,
            type: AutomatizationType::InvoiceDueReminder,
            dateTrigger: now()->addDays(3),
            dueOffsetDays: 2,
            itemNames: null,
            isActive: false,
        );

        $updated = $this->service->update($automatization, $dto);

        $this->assertSame(AutomatizationType::InvoiceDueReminder, $updated->type);
        $this->assertFalse($updated->is_active);
    }

    public function test_delete_soft_deletes_automatization(): void
    {
        $automatization = Automatization::factory()->create(['user_id' => $this->user->id]);

        $this->service->delete($automatization);

        $this->assertSoftDeleted('automatizations', ['id' => $automatization->id]);
    }
}
