<?php

namespace Database\Factories;

use App\Enums\AutomatizationType;
use App\Models\Automatization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Automatization>
 */
class AutomatizationFactory extends Factory
{
    protected $model = Automatization::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient_id' => null,
            'name' => $this->faker->sentence(3),
            'type' => AutomatizationType::InvoiceReport,
            'date_trigger' => now()->addDay()->toDateString(),
            'due_offset_days' => null,
            'is_active' => true,
            'item_names' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
