<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\InvoiceDTO;
use App\Exceptions\DuplicateInvoiceNumberException;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\VatType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        private readonly InvoiceStatsCalculator $invoiceStatsCalculator,
        private readonly RecipientService $recipientService,
    ) {
    }

    /**
     * @return EloquentCollection<int, Invoice>
     */
    public function getInvoices(int $userId): EloquentCollection
    {
        return Invoice::forUser($userId)
            ->with('invoiceStatus')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * @return array{total_invoiced: float, paid: float, awaiting: float, overdue: float, draft: float}
     */
    public function getInvoiceStats(int $userId): array
    {
        return $this->invoiceStatsCalculator->calculateForCollection(
            $this->getInvoices($userId),
            now()->startOfDay(),
        );
    }

    public function createInvoice(InvoiceDTO $data): Invoice
    {
        $this->ensureRecipientBelongsToUser($data);

        try {
            return DB::transaction(function () use ($data) {
                $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);
                $payload = $this->buildInvoicePayload($data);

                $invoice = Invoice::create([
                    'user_id' => $data->userId,
                    ...$payload['attributes'],
                    'invoice_status_id' => $draftStatus?->id,
                ]);

                $this->persistInvoiceItems($invoice, $payload['item_rows']);

                return $invoice;
            });
        } catch (UniqueConstraintViolationException) {
            throw new DuplicateInvoiceNumberException($data->number);
        }
    }

    public function updateInvoice(Invoice $invoice, InvoiceDTO $data): Invoice
    {
        $this->ensureRecipientBelongsToUser($data);

        try {
            return DB::transaction(function () use ($invoice, $data) {
                $payload = $this->buildInvoicePayload($data);

                $invoice->update($payload['attributes']);

                $this->persistInvoiceItems($invoice, $payload['item_rows'], replace: true);

                return $invoice->fresh('items');
            });
        } catch (UniqueConstraintViolationException) {
            throw new DuplicateInvoiceNumberException($data->number);
        }
    }

    public function updateStatus(Invoice $invoice, int $invoiceStatusId): void
    {
        $invoice->update(['invoice_status_id' => $invoiceStatusId]);
    }

    public function delete(Invoice $invoice): void
    {
        $invoice->delete();
    }

    /**
     * @return array{
     *   item_rows: array<int, array<string, mixed>>,
     *   attributes: array<string, mixed>,
     * }
     */
    private function buildInvoicePayload(InvoiceDTO $data): array
    {
        $woVatTotal = 0.0;
        $vatTotal = 0.0;
        $itemRows = [];

        foreach ($data->items as $position => $item) {
            $lineWoVat = round($item->quantity * $item->unitPrice, 2);
            $lineVat = round($this->calculateLineVat($lineWoVat, $item->vatTypeId), 2);
            $lineTotalWithVat = round($lineWoVat + $lineVat, 2);

            $woVatTotal += $lineWoVat;
            $vatTotal += $lineVat;

            $itemRows[] = [
                'vat_type_id' => $item->vatTypeId,
                'name' => $item->name,
                'unit' => $item->unit,
                'quantity' => $item->quantity,
                'unit_price' => $item->unitPrice,
                'unit_wo_vat' => $item->unitPrice,
                'position' => $position,
                'line_wo_vat' => $lineWoVat,
                'vat' => $lineVat,
                'line_total' => $lineTotalWithVat,
            ];
        }

        return [
            'item_rows' => $itemRows,
            'attributes' => [
                'recipient_id' => $data->recipientId,
                'number' => $data->number,
                'varsym' => $data->variableSymbol,
                'issue_date' => $data->issueDate,
                'due_date' => $data->dueDate,
                'currency_id' => $data->currencyId,
                'recipient_name' => $data->recipient->recipientName,
                'recipient_street' => $data->recipient->recipientStreet,
                'recipient_street_num' => $data->recipient->recipientStreetNum,
                'recipient_city' => $data->recipient->recipientCity,
                'recipient_state' => $data->recipient->recipientState,
                'recipient_ico' => $data->recipient->recipientIco,
                'recipient_dic' => $data->recipient->recipientDic,
                'recipient_ic_dph' => $data->recipient->recipientIcDph,
                'iban' => $data->recipient->recipientIban,
                'wo_vat_price' => round($woVatTotal, 2),
                'vat_price' => round($vatTotal, 2),
                'total_price' => round($woVatTotal + $vatTotal, 2),
            ],
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $itemRows
     */
    private function persistInvoiceItems(Invoice $invoice, array $itemRows, bool $replace = false): void
    {
        if ($replace) {
            $invoice->items()->delete();
        }

        foreach ($itemRows as $row) {
            $invoice->items()->create($row);
        }
    }

    private function calculateLineVat(float $lineWoVat, ?int $vatTypeId): float
    {
        $vatType = VatType::find($vatTypeId);

        if ($vatType === null || in_array(strtoupper((string) $vatType->code), ['MIMO', 'OSVO'], true)) {
            return 0.0;
        }

        return $lineWoVat * ($vatType->rate / 100);
    }

    private function ensureRecipientBelongsToUser(InvoiceDTO $data): void
    {
        if ($data->recipientId === null) {
            return;
        }

        $this->recipientService->findForUserOrFail($data->userId, $data->recipientId);
    }

    public function getSuggestedNumber(int $userId): string
    {
        $today = now()->format('Ymd');
        $existingCount = Invoice::forUser($userId)
            ->where(function ($q) use ($today) {
                $q->where('number', $today)
                    ->orWhere('number', 'like', $today . '-%');
            })
            ->count();

        return ($existingCount === 0)
            ? $today
            : $today . '-' . ($existingCount + 1);
    }
}
