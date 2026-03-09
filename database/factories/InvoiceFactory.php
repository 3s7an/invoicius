<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'number' => $this->faker->unique()->numerify('INV-####'),
            'varsym' => $this->faker->numerify('####'),
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'wo_vat_price' => 100.00,
            'vat_price' => 0.00,
            'total_price' => 100.00,
        ];
    }
}
