<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['name' => 'Euro', 'symbol' => '€'],
            ['name' => 'Česká koruna', 'symbol' => 'Kč'],
            ['name' => 'Americký dolár', 'symbol' => '$'],
        ];

        foreach ($currencies as $row) {
            Currency::updateOrCreate(
                ['symbol' => $row['symbol']],
                ['name' => $row['name']]
            );
        }
    }
}
