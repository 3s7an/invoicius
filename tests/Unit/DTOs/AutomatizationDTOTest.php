<?php

declare(strict_types=1);

namespace Tests\Unit\DTOs;

use App\DTOs\AutomatizationDTO;
use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomatizationDTOTest extends TestCase
{
    use RefreshDatabase;

    public function test_from_validated_parses_date_trigger_without_timezone_shift(): void
    {
        $user = User::factory()->create();

        $dto = AutomatizationDTO::fromValidated([
            'type' => AutomatizationType::InvoiceAutoGen->value,
            'date_trigger' => '2026-06-27',
            'recipient_id' => null,
            'item_names' => ['Položka'],
        ], $user->id);

        $this->assertSame('2026-06-27', $dto->dateTrigger->toDateString());
    }

    public function test_model_serializes_date_trigger_as_date_string(): void
    {
        $automatization = Automatization::factory()->create([
            'date_trigger' => '2026-06-27',
        ]);

        $this->assertSame('2026-06-27', $automatization->toArray()['date_trigger']);
    }
}
