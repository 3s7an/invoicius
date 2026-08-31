<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoicePdfService
{
    public function __construct(
        private readonly IbanService $ibanService,
    ) {}

    public function getPdfDownloadResponse(Invoice $invoice): \Spatie\LaravelPdf\PdfBuilder
    {
        $invoice->load(['items', 'currency', 'recipient', 'user.companyLogo']);
        $issuer = $invoice->user;
        $currencySymbol = $invoice->currency !== null ? $invoice->currency->symbol : '€';

        $logoDataUrl = null;
        if ($issuer->companyLogo) {
            $path = Storage::disk('public')->path($issuer->companyLogo->link);
            if (file_exists($path)) {
                $mime = mime_content_type($path) ?: 'image/png';
                $logoDataUrl = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        $paymentIban = $this->ibanService->normalizeIban($issuer->iban);

        return Pdf::view('pdf.invoice', [
            'invoice' => $invoice,
            'issuer' => $issuer,
            'currencySymbol' => $currencySymbol,
            'logoDataUrl' => $logoDataUrl,
            'paymentIban' => $paymentIban,
            'unitLabels' => __('invoice.units'),
        ])
            ->name('invoice-' . preg_replace('/[^a-z0-9-]/i', '-', $invoice->number) . '.pdf')
            ->download();
    }
}