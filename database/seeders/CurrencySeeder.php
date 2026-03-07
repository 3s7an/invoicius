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
            ['name' => 'Czech Koruna', 'symbol' => 'Kč'],
            ['name' => 'US Dollar', 'symbol' => '$'],
        ];

        foreach ($currencies as $row) {
            Currency::firstOrCreate(
                ['symbol' => $row['symbol']],
                ['name' => $row['name']]
            );
        }
    }
}
