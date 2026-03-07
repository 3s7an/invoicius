<?php

namespace Database\Seeders;

use App\Models\InvoiceColor;
use Illuminate\Database\Seeder;

class InvoiceColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Modrá', 'hex' => '#3B82F6'],
            ['name' => 'Zelená', 'hex' => '#22C55E'],
            ['name' => 'Červená', 'hex' => '#EF4444'],
            ['name' => 'Oranžová', 'hex' => '#F97316'],
            ['name' => 'Fialová', 'hex' => '#A855F7'],
            ['name' => 'Tyrkysová', 'hex' => '#06B6D4'],
            ['name' => 'Ružová', 'hex' => '#EC4899'],
            ['name' => 'Sivá', 'hex' => '#6B7280'],
        ];

        foreach ($colors as $row) {
            InvoiceColor::firstOrCreate(
                ['hex' => $row['hex']],
                ['name' => $row['name']]
            );
        }
    }
}
