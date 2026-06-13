<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceStatus;
use App\Models\User;
use App\Models\VatType;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            $this->command->warn('Žiadny používateľ. Najprv sa zaregistruj alebo spusti DatabaseSeeder.');

            return;
        }

        if (Invoice::where('user_id', $user->id)->exists()) {
            $this->command->warn('Faktúry pre používateľa už existujú, preskakujem InvoiceSeeder.');

            return;
        }

        $statusIds = InvoiceStatus::pluck('id', 'code');
        $currencyId = Currency::where('symbol', '€')->value('id');
        $vatTypeId = VatType::where('code', '23')->value('id');

        $invoicesData = [
            [
                'number' => '2026-001',
                'recipient_name' => 'Firma s.r.o.',
                'payment_type' => 'PREVOD',
                'status_code' => 'paid',
                'items' => [
                    ['name' => 'Konzultácie', 'unit' => 'hod', 'quantity' => 10, 'unit_price' => 50.00, 'vat' => 23, 'line_total' => 600.00],
                    ['name' => 'Hosting 1 rok', 'unit' => 'ks', 'quantity' => 1, 'unit_price' => 120.00, 'vat' => 23, 'line_total' => 144.00],
                ],
            ],
            [
                'number' => '2026-002',
                'recipient_name' => 'Zákazník XY',
                'payment_type' => 'PLATOBNOU KARTOU',
                'status_code' => 'sent',
                'items' => [
                    ['name' => 'Dizajn loga', 'unit' => 'ks', 'quantity' => 1, 'unit_price' => 250.00, 'vat' => 23, 'line_total' => 300.00],
                    ['name' => 'Úpravy', 'unit' => 'hod', 'quantity' => 2, 'unit_price' => 40.00, 'vat' => 23, 'line_total' => 96.00],
                ],
            ],
            [
                'number' => '2026-003',
                'recipient_name' => 'Občan A',
                'payment_type' => 'HOTOVOST',
                'status_code' => 'draft',
                'items' => [
                    ['name' => 'Oprava PC', 'unit' => 'ks', 'quantity' => 1, 'unit_price' => 80.00, 'vat' => 23, 'line_total' => 96.00],
                    ['name' => 'Čistenie', 'unit' => 'ks', 'quantity' => 1, 'unit_price' => 25.00, 'vat' => 23, 'line_total' => 30.00],
                ],
            ],
        ];

        $vatRate = 0.23;

        foreach ($invoicesData as $data) {
            $itemsData = $data['items'];
            $statusCode = $data['status_code'];
            unset($data['items'], $data['status_code']);

            $issueDate = now()->subDays(random_int(5, 60));
            $dueDate = $issueDate->copy()->addDays(14);
            $totalPrice = array_sum(array_column($itemsData, 'line_total'));
            $woVatPrice = round($totalPrice / (1 + $vatRate), 2);
            $vatPrice = round($totalPrice - $woVatPrice, 2);

            $invoice = Invoice::create(array_merge([
                'user_id' => $user->id,
                'varsym' => str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT),
                'recipient_street' => 'Hlavná 1',
                'recipient_street_num' => '1',
                'recipient_city' => 'Bratislava',
                'recipient_state' => 'Slovensko',
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'iban' => 'SK31 1200 0000 1987 4263 7541',
                'total_price' => $totalPrice,
                'vat_price' => $vatPrice,
                'wo_vat_price' => $woVatPrice,
                'invoice_status_id' => $statusIds[$statusCode] ?? null,
                'currency_id' => $currencyId,
                'notes' => null,
                'sequence_number' => (int) substr($data['number'], -3),
                'year' => (int) substr($data['number'], 0, 4),
            ], $data));

            foreach ($itemsData as $position => $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'vat_type_id' => $vatTypeId,
                    'name' => $item['name'],
                    'unit' => $item['unit'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'unit_wo_vat' => round($item['unit_price'] / (1 + $vatRate), 2),
                    'vat' => $item['vat'],
                    'discount' => 0,
                    'position' => $position + 1,
                    'line_total' => $item['line_total'],
                ]);
            }
        }
    }
}
