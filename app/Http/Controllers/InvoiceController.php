<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\Forms\InvoiceFormData;
use App\DTOs\InvoiceDTO;
use App\Http\Requests\StoreInvoiceRequest;
use App\Services\InvoiceFormDataService;
use App\Services\InvoiceService;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceStatusRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\InvoicePdfService;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly InvoiceFormDataService $invoiceFormDataService,
        private readonly InvoicePdfService $invoicePdfService,
    ) {
    }

    public function index(Request $request): Response
    {
        $data = $this->invoiceFormDataService->forIndexWithFormDeps($request->user()->id);

        return Inertia::render('Invoices/Index', $data);
    }

    public function create(Request $request): Response
    {
        $userId = $request->user()->id;
        $createdRecipientId = $request->query('created_recipient_id') ?? session('created_recipient_id');
        if ($createdRecipientId) {
            session()->forget('created_recipient_id');
        }

        $data = $this->invoiceFormDataService->forCreate(
            $userId,
            $createdRecipientId ? (int) $createdRecipientId : null
        );

        return Inertia::render('Invoices/Create', $data);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $formData = InvoiceFormData::fromValidated($request->validated());
        $dto = InvoiceDTO::fromFormData($formData, $request->user()->id);
        $this->invoiceService->createInvoice($dto);

        return to_route('invoices')
            ->with('success', __('messages.invoice_created'));
    }

    public function edit(Request $request, Invoice $invoice): Response
    {
        $this->authorize('view', $invoice);

        $userId = $request->user()->id;
        $editData = $this->invoiceFormDataService->forEdit($invoice, $userId);
        $indexData = $this->invoiceFormDataService->forIndexWithFormDeps($userId);

        return Inertia::render('Invoices/Index', [
            ...$indexData,
            'editing_invoice'     => $editData['invoice'],
            'default_currency_id' => $editData['default_currency_id'],
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->authorize('update', $invoice);

        $formData = InvoiceFormData::fromValidated($request->validated());
        $dto = InvoiceDTO::fromFormData($formData, $request->user()->id);
        $this->invoiceService->updateInvoice($invoice, $dto);

        return to_route('invoices')
            ->with('success', __('messages.invoice_updated'));
    }

    public function updateStatus(UpdateInvoiceStatusRequest $request, Invoice $invoice): RedirectResponse
    {   
        $this->authorize('update', $invoice);
        
        $this->invoiceService->updateStatus($invoice, (int) $request->validated('invoice_status_id'));

        return back()
            ->with('success', __('messages.invoice_status_updated'));
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->authorize('delete', $invoice);
        $this->invoiceService->delete($invoice);

        return to_route('invoices')
            ->with('success', __('messages.invoice_deleted'));
    }

    /**
     * @return \Spatie\LaravelPdf\PdfBuilder|Response
     */
    public function downloadPdf(Invoice $invoice): mixed
    {
        $this->authorize('view', $invoice);

        return $this->invoicePdfService->getPdfDownloadResponse($invoice);
    }
}
