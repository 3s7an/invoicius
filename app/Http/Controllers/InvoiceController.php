<?php

namespace App\Http\Controllers;

use App\Contracts\InvoiceServiceInterface;
use App\DTOs\CreateInvoiceData;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
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
        $data = $this->invoiceService->getIndexData($request->user()->id);

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
            ->with('success', 'Faktúra bola vytvorená.');
    }

    public function edit(Request $request, Invoice $invoice): Response
    {
        $this->authorize('view', $invoice);

        $data = $this->invoiceService->getEditFormData($invoice, $request->user()->id);

        return Inertia::render('Invoices/Edit', $data);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->authorize('update', $invoice);

        $data = CreateInvoiceData::fromValidated($request->validated(), $request->user()->id);
        $this->invoiceService->updateInvoice($invoice, $data);

        return redirect()
            ->route('invoices')
            ->with('success', 'Faktúra bola upravená.');
    }

    public function updateStatus(UpdateInvoiceStatusRequest $request, Invoice $invoice): RedirectResponse
    {   
        $this->authorize('update', $invoice);
        
        $this->invoiceService->updateStatus($invoice, (int) $request->validated('invoice_status_id'));

        return redirect()
            ->back()
            ->with('success', 'Stav faktúry bol aktualizovaný.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->authorize('delete', $invoice);
        $this->invoiceService->delete($invoice);

        return redirect()
            ->route('invoices')
            ->with('success', 'Faktúra bola zmazaná.');
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
