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
            ['name' => 'Blue', 'hex' => '#3B82F6'],
            ['name' => 'Green', 'hex' => '#22C55E'],
            ['name' => 'Red', 'hex' => '#EF4444'],
            ['name' => 'Orange', 'hex' => '#F97316'],
            ['name' => 'Purple', 'hex' => '#A855F7'],
            ['name' => 'Turquoise', 'hex' => '#06B6D4'],
            ['name' => 'Pink', 'hex' => '#EC4899'],
            ['name' => 'Gray', 'hex' => '#6B7280'],
        ];

        foreach ($colors as $row) {
            InvoiceColor::updateOrCreate(
                ['hex' => $row['hex']],
                ['name' => $row['name']]
            );
        }
    }
}
