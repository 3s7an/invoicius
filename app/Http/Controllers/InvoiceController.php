<?php

namespace App\Http\Controllers;

use App\Contracts\InvoiceServiceInterface;
use App\DTOs\CreateInvoiceData;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceStatusRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceServiceInterface $invoiceService,
    ) {
    }

    public function index(Request $request): Response
    {
        try {
            $data = $this->invoiceService->getIndexData($request->user()->id);
        } catch (\Throwable $e) {
            Log::error('Invoices index getIndexData failed', [
                'user_id' => $request->user()->id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $data = [
                'invoices' => collect([]),
                'invoice_stats' => ['total_invoiced' => 0, 'paid' => 0, 'awaiting' => 0, 'overdue' => 0],
                'invoice_statuses' => collect([]),
            ];
        }

        return Inertia::render('Invoices', [
            'invoices' => $data['invoices'],
            'invoice_stats' => $data['invoice_stats'],
            'invoice_statuses' => $data['invoice_statuses'],
        ]);
    }

    public function create(Request $request): Response
    {
        $userId = $request->user()->id;
        $createdRecipientId = $request->query('created_recipient_id') ?? session('created_recipient_id');
        if ($createdRecipientId) {
            session()->forget('created_recipient_id');
        }

        $data = $this->invoiceService->getCreateFormData(
            $userId,
            $createdRecipientId ? (int) $createdRecipientId : null
        );

        return Inertia::render('Invoices/Create', $data);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = CreateInvoiceData::fromValidated($request->validated(), $request->user()->id);
        $this->invoiceService->createInvoice($data);

        return redirect()
            ->route('invoices')
            ->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice): never
    {
        abort(404);
    }

    public function edit(Invoice $invoice): Response
    {
        $this->authorize('view', $invoice);
        $invoice->load('items');

        return Inertia::render('Invoices/Edit', [
            'invoice' => $invoice,
        ]);
    }

    public function update(Invoice $invoice): never
    {
        abort(404);
    }

    public function updateStatus(UpdateInvoiceStatusRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->invoiceService->updateStatus($invoice, (int) $request->validated('invoice_status_id'));

        return redirect()
            ->back()
            ->with('success', 'Invoice status updated.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->authorize('delete', $invoice);
        $this->invoiceService->delete($invoice);

        return redirect()
            ->route('invoices')
            ->with('success', 'Invoice deleted.');
    }

    /**
     * @return \Spatie\LaravelPdf\PdfBuilder|Response
     */
    public function downloadPdf(Invoice $invoice): mixed
    {
        $this->authorize('view', $invoice);

        return $this->invoiceService->getPdfDownloadResponse($invoice);
    }
}
