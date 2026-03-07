<?php

namespace Database\Seeders;

use App\Models\InvoiceStatus;
use Illuminate\Database\Seeder;

class InvoiceStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['code' => 'draft', 'name' => 'Draft'],
            ['code' => 'sent', 'name' => 'Sent'],
            ['code' => 'paid', 'name' => 'Paid'],
            ['code' => 'overdue', 'name' => 'Overdue'],
        ];

        foreach ($statuses as $row) {
            InvoiceStatus::firstOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name']]
            );
        }
    }
}
