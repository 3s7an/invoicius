<?php

namespace Database\Seeders;

use App\Models\InvoiceStatus;
use Illuminate\Database\Seeder;

class InvoiceStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['code' => 'draft', 'name' => 'Koncept'],
            ['code' => 'sent', 'name' => 'Odoslaná'],
            ['code' => 'paid', 'name' => 'Uhradená'],
            ['code' => 'overdue', 'name' => 'Po splatnosti'],
        ];

        foreach ($statuses as $row) {
            InvoiceStatus::updateOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name']]
            );
        }
    }
}
