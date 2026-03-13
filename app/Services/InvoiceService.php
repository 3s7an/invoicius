<?php

namespace App\Services;

use App\Contracts\InvoiceServiceInterface;
use App\Contracts\RecipientServiceInterface;
use App\DTOs\CreateInvoiceData;
use App\DTOs\CreateInvoiceItemData;
use App\DTOs\CreateInvoiceRecipientData;
use App\Exceptions\DuplicateInvoiceNumberException;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceColor;
use App\Models\InvoiceItem;
use App\Models\InvoiceStatus;
use App\Models\Recipient;
use App\Models\User;
use App\Models\VatType;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoiceService implements InvoiceServiceInterface
{
    public function __construct(
        private readonly RecipientServiceInterface $recipientService,
    ) {
    }

    public function getInvoices(int $userId): Collection
    {
        return Invoice::forUser($userId)
            ->with('invoiceStatus')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * @return array{total_invoiced: float, paid: float, awaiting: float, overdue: float}
     */
    public function getInvoiceStats(int $userId): array
    {
        $base = Invoice::forUser($userId);
        $paidStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_PAID);
        $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);
        $paidStatusId = $paidStatus?->id;
        $draftStatusId = $draftStatus?->id;
        $today = now()->startOfDay();

        $totalInvoiced = (clone $base)->sum('total_price');
        $paid = $paidStatusId
            ? (clone $base)->where('invoice_status_id', $paidStatusId)->sum('total_price')
            : 0.0;

        $overdueQuery = (clone $base)->whereDate('due_date', '<', $today);
        if ($paidStatusId !== null) {
            $overdueQuery = $overdueQuery->where('invoice_status_id', '!=', $paidStatusId);
        }
        if ($draftStatusId !== null) {
            $overdueQuery = $overdueQuery->where('invoice_status_id', '!=', $draftStatusId);
        }
        $overdue = (float) $overdueQuery->sum('total_price');

        $excludeIds = array_filter([$paidStatusId, $draftStatusId]);
        $awaitingQuery = clone $base;
        if ($excludeIds) {
            $awaitingQuery = $awaitingQuery->whereNotIn('invoice_status_id', $excludeIds);
        }
        $awaitingQuery = $awaitingQuery->where(function ($q) use ($today) {
            $q->whereNull('due_date')->orWhereDate('due_date', '>=', $today);
        });
        $awaiting = (float) $awaitingQuery->sum('total_price');

        return [
            'total_invoiced' => (float) $totalInvoiced,
            'paid' => (float) $paid,
            'awaiting' => (float) max(0, $awaiting),
            'overdue' => $overdue,
        ];
    }


    private function calculateLineVat(float $lineWoVat, ?int $vatTypeId): float
    {
        if (! $vatTypeId) {
            return 0.0;
        }
        $vatType = VatType::find($vatTypeId);
        if (! $vatType || in_array(strtoupper((string) $vatType->code), ['MIMO', 'OSVO'], true)) {
            return 0.0;
        }

        return $lineWoVat * ($vatType->rate / 100);
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

    public function createInvoice(CreateInvoiceData $data): Invoice
    {
        if ($data->recipientId !== null) {
            $recipientBelongsToUser = Recipient::forUser($data->userId)
                ->where('id', $data->recipientId)
                ->exists();

            if (! $recipientBelongsToUser) {
                abort(403, 'Recipient does not belong to this user.');
            }
        }

        try {
            return DB::transaction(function () use ($data) {
                $draftStatus = InvoiceStatus::getByCode(InvoiceStatus::CODE_DRAFT);

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

                $invoice = Invoice::create([
                    'user_id' => $data->userId,
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
                    'invoice_status_id' => $draftStatus?->id,
                ]);

                foreach ($itemRows as $row) {
                    $invoice->items()->create($row);
                }

                return $invoice;
            });
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateInvoiceNumberException($data->number);
        } catch (\Throwable $e) {
            Log::error('Failed to create invoice', [
                'user_id' => $data->userId,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function updateInvoice(Invoice $invoice, CreateInvoiceData $data): Invoice
    {
        if ($data->recipientId !== null) {
            $recipientBelongsToUser = Recipient::forUser($data->userId)
                ->where('id', $data->recipientId)
                ->exists();

            if (! $recipientBelongsToUser) {
                abort(403, 'Recipient does not belong to this user.');
            }
        }

        try {
            return DB::transaction(function () use ($invoice, $data) {
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

                $invoice->update([
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
                ]);

                $invoice->items()->delete();
                foreach ($itemRows as $row) {
                    $invoice->items()->create($row);
                }

                return $invoice->fresh('items');
            });
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateInvoiceNumberException($data->number);
        } catch (\Throwable $e) {
            Log::error('Failed to update invoice', [
                'invoice_id' => $invoice->id,
                'user_id' => $data->userId,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
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
     * @return \Spatie\LaravelPdf\PdfBuilder
     */
    public function getPdfDownloadResponse(Invoice $invoice)
    {
        $invoice->load(['items', 'currency', 'recipient', 'user.invoiceColor', 'user.companyLogo']);
        $issuer = $invoice->user;
        $accentColor = $issuer->invoiceColor?->hex ?? '#2563eb';
        $currencySymbol = $invoice->currency?->symbol ?? '€';

        $logoDataUrl = null;
        if ($issuer->companyLogo) {
            $path = Storage::disk('public')->path($issuer->companyLogo->link);
            if (file_exists($path)) {
                $mime = mime_content_type($path) ?: 'image/png';
                $logoDataUrl = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return Pdf::view('pdf.invoice', [
            'invoice' => $invoice,
            'issuer' => $issuer,
            'accentColor' => $accentColor,
            'currencySymbol' => $currencySymbol,
            'logoDataUrl' => $logoDataUrl,
        ])
            ->name('invoice-' . preg_replace('/[^a-z0-9-]/i', '-', $invoice->number) . '.pdf')
            ->download();
    }

    public function getIndexData(int $userId): array
    {
        return [
            'invoices' => $this->getInvoices($userId),
            'invoice_stats' => $this->getInvoiceStats($userId),
            'invoice_statuses' => InvoiceStatus::orderBy('id')->get(['id', 'code', 'name']),
        ];
    }

    public function getCreateFormData(int $userId, ?int $createdRecipientId): array
    {
        $user = User::find($userId);
        $preselectedRecipient = null;
        if ($createdRecipientId) {
            $preselectedRecipient = Recipient::forUser($userId)->find($createdRecipientId);
        }

        return [
            'recipients' => $this->recipientService->listForUser($userId),
            'suggested_number' => $this->getSuggestedNumber($userId),
            'preselected_recipient' => $preselectedRecipient,
            'currencies' => Currency::orderBy('name')->get(['id', 'name', 'symbol']),
            'vat_types' => VatType::orderBy('code')->get(['id', 'code', 'rate']),
            'default_currency_id' => $user?->currency_id,
            'invoice_colors' => InvoiceColor::orderBy('name')->get(['id', 'name', 'hex']),
        ];
    }

    public function getEditFormData(Invoice $invoice, int $userId): array
    {
        $invoice->load(['items', 'recipient']);

        return [
            'invoice' => $invoice,
            'recipients' => $this->recipientService->listForUser($userId),
            'currencies' => Currency::orderBy('name')->get(['id', 'name', 'symbol']),
            'vat_types' => VatType::orderBy('code')->get(['id', 'code', 'rate']),
            'default_currency_id' => $invoice->currency_id,
            'invoice_colors' => InvoiceColor::orderBy('name')->get(['id', 'name', 'hex']),
        ];
    }

    public function generateFromAutomatization(int $userId, int $recipientId): Invoice
    {
        $user = User::with('defaultVatType')->findOrFail($userId);
        $recipient = Recipient::forUser($userId)->findOrFail($recipientId);

        $defaultCurrencyId = $user->currency_id ?? Currency::orderBy('id')->value('id');
        $defaultVatTypeId = $user->default_vat_type_id;

        $data = new CreateInvoiceData(
            userId: $userId,
            number: $this->getSuggestedNumber($userId),
            variableSymbol: $this->getSuggestedNumber($userId),
            issueDate: Carbon::today(),
            dueDate: Carbon::today()->addDays(14),
            currencyId: $defaultCurrencyId,
            recipientId: $recipientId,
            recipient: new CreateInvoiceRecipientData(
                recipientName: $recipient->company_name ?? $recipient->name,
                recipientStreet: $recipient->street,
                recipientStreetNum: $recipient->street_num,
                recipientCity: $recipient->city,
                recipientState: $recipient->state,
                recipientIco: $recipient->ico,
                recipientDic: $recipient->dic,
                recipientIcDph: $recipient->ic_dph,
                recipientIban: $recipient->iban,
            ),
            items: [
                new CreateInvoiceItemData(
                    name: '',
                    quantity: 1,
                    unitPrice: 0,
                    unit: '',
                    vatTypeId: $defaultVatTypeId,
                ),
            ],
        );

        return $this->createInvoice($data);
    }
}
