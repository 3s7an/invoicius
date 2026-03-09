<?php

namespace Database\Seeders;

use App\Models\VatType;
use Illuminate\Database\Seeder;

class VatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['code' => '23', 'rate' => 23.00],
            ['code' => '19', 'rate' => 19.00],
            ['code' => '5', 'rate' => 5.00],
            ['code' => 'MIMO', 'rate' => 0.00],
            ['code' => 'OSVO', 'rate' => 0.00],
        ];

        foreach ($types as $type) {
            VatType::updateOrCreate(
                ['code' => $type['code']],
                ['rate' => $type['rate']],
            );
        }
    }
}
