<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoicePdfService
{
    public function __construct(
        private readonly PaymentQrService $paymentQrService,
    ) {}

    public function getPdfDownloadResponse(Invoice $invoice)
    {
        $invoice->load(['items', 'currency', 'recipient', 'user.companyLogo']);
        $issuer = $invoice->user;
        $currencySymbol = $invoice->currency?->symbol ?? '€';

        $logoDataUrl = null;
        if ($issuer->companyLogo) {
            $path = Storage::disk('public')->path($issuer->companyLogo->link);
            if (file_exists($path)) {
                $mime = mime_content_type($path) ?: 'image/png';
                $logoDataUrl = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        $paymentIban = $this->paymentQrService->normalizeIban($issuer->iban);
        $paymentQrDataUrl = $paymentIban !== null
            ? $this->paymentQrService->generateDataUriForInvoice($invoice, $issuer)
            : null;

        return Pdf::view('pdf.invoice', [
            'invoice' => $invoice,
            'issuer' => $issuer,
            'currencySymbol' => $currencySymbol,
            'logoDataUrl' => $logoDataUrl,
            'paymentIban' => $paymentIban,
            'paymentQrDataUrl' => $paymentQrDataUrl,
            'unitLabels' => [
                'pcs' => 'ks',
                'hrs' => 'hod',
                'days' => 'dni',
                'kg' => 'kg',
                'm' => 'm',
                'm²' => 'm²',
            ],
        ])
            ->name('invoice-' . preg_replace('/[^a-z0-9-]/i', '-', $invoice->number) . '.pdf')
            ->download();
    }
}