<?php

namespace Database\Factories;

use App\Models\Recipient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipientFactory extends Factory
{
    protected $model = Recipient::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'company_name' => $this->faker->company(),
            'street' => $this->faker->streetName(),
            'street_num' => $this->faker->buildingNumber(),
            'city' => $this->faker->city(),
            'zip' => $this->faker->postcode(),
            'state' => $this->faker->countryCode(),
            'ico' => $this->faker->numerify('########'),
        ];
    }
}
