<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use DateTimeInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use rikudou\SkQrPayment\QrPayment;
use Rikudou\Iban\Iban\IBAN;

class PaymentQrService
{
    /**
     * PNG data URI for embedding in PDF, or null when payment QR cannot be built.
     */
    public function generateDataUriForInvoice(Invoice $invoice, User $issuer): ?string
    {
        $iban = $this->normalizeIban($issuer->iban);
        $amount = (float) ($invoice->total_price ?? 0);

        if ($iban === null || $amount <= 0) {
            return null;
        }

        $payload = $this->buildPayload($invoice, $issuer, $iban, $amount);
        if ($payload === null) {
            return null;
        }

        return $this->renderDataUri($payload);
    }

    public function normalizeIban(?string $iban): ?string
    {
        if ($iban === null || trim($iban) === '') {
            return null;
        }

        $normalized = strtoupper(preg_replace('/\s+/', '', $iban) ?? '');

        return preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]+$/', $normalized) === 1
            ? $normalized
            : null;
    }

    private function buildPayload(Invoice $invoice, User $issuer, string $iban, float $amount): ?string
    {
        if ($this->isSkIban($iban)) {
            try {
                return $this->buildPayBySquarePayload($invoice, $issuer, $iban, $amount);
            } catch (\Throwable) {
                // xz missing or Pay by Square encoding failed — fall back to EPC
            }
        }

        return $this->buildEpcPayload($invoice, $issuer, $iban, $amount);
    }

    private function buildPayBySquarePayload(Invoice $invoice, User $issuer, string $iban, float $amount): string
    {
        $payment = QrPayment::fromIBAN($iban);
        $payment
            ->setAmount($amount)
            ->setCurrency($this->resolveCurrencyCode($invoice))
            ->setPayeeName($this->resolvePayeeName($issuer))
            ->setComment($this->buildPaymentNote($invoice));

        if ($invoice->varsym !== null && $invoice->varsym !== '') {
            $payment->setVariableSymbol(preg_replace('/\D/', '', (string) $invoice->varsym) ?: (string) $invoice->varsym);
        }

        if ($invoice->due_date instanceof DateTimeInterface) {
            $payment->setDueDate(\DateTimeImmutable::createFromInterface($invoice->due_date));
        }

        return $payment->getQrString();
    }

    private function buildEpcPayload(Invoice $invoice, User $issuer, string $iban, float $amount): string
    {
        $currency = $this->resolveCurrencyCode($invoice);
        $beneficiary = $this->truncate($this->resolvePayeeName($issuer), 70);
        $amountLine = sprintf('%s%.2f', $currency, $amount);
        $reference = $this->truncate($this->buildPaymentNote($invoice), 140);

        $lines = [
            'BCD',
            '002',
            '1',
            'SCT',
            '',
            $beneficiary,
            $iban,
            $amountLine,
            '',
            '',
            $reference,
        ];

        return implode("\n", $lines);
    }

    private function renderDataUri(string $payload): string
    {
        $qrCode = new QrCode(
            data: $payload,
            size: 300,
            margin: 10,
        );

        return (new PngWriter)->write($qrCode)->getDataUri();
    }

    private function isSkIban(string $iban): bool
    {
        return str_starts_with($iban, 'SK');
    }

    private function resolvePayeeName(User $issuer): string
    {
        $name = trim((string) ($issuer->company_name ?: $issuer->name));

        return $name !== '' ? $name : 'Príjemca platby';
    }

    private function buildPaymentNote(Invoice $invoice): string
    {
        $parts = array_filter([
            $invoice->number ? 'Faktúra '.$invoice->number : null,
            $invoice->varsym ? 'VS '.$invoice->varsym : null,
        ]);

        return implode(', ', $parts) ?: 'Úhrada faktúry';
    }

    private function resolveCurrencyCode(Invoice $invoice): string
    {
        $symbol = $invoice->currency?->symbol;
        $name = strtolower((string) ($invoice->currency?->name ?? ''));

        return match ($symbol) {
            '€' => 'EUR',
            'Kč' => 'CZK',
            '$' => 'USD',
            default => match (true) {
                str_contains($name, 'euro') => 'EUR',
                str_contains($name, 'koruna'), str_contains($name, 'czech'), str_contains($name, 'česk') => 'CZK',
                str_contains($name, 'dollar'), str_contains($name, 'dolár'), str_contains($name, 'dolar') => 'USD',
                default => 'EUR',
            },
        };
    }

    private function truncate(string $value, int $maxLength): string
    {
        if (mb_strlen($value) <= $maxLength) {
            return $value;
        }

        return mb_substr($value, 0, $maxLength);
    }
}
