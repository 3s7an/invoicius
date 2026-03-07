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
        $codes = ['23', '19', '5', 'MIMO', 'OSVO'];

        foreach ($codes as $code) {
            VatType::firstOrCreate(['code' => $code]);
        }
    }
}
