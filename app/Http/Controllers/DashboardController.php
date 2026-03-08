<?php

namespace App\Http\Controllers;

use App\Contracts\InvoiceServiceInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly InvoiceServiceInterface $invoiceService,
    ) {
    }

    public function index(Request $request): Response
    {
        $user = $request->user();

        try {
            $invoiceStats = $this->invoiceService->getInvoiceStats($user->id);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard getInvoiceStats failed', ['user_id' => $user->id, 'exception' => $e->getMessage()]);
            $invoiceStats = ['total_invoiced' => 0, 'paid' => 0, 'awaiting' => 0, 'overdue' => 0];
        }

        $currencySymbol = '€';
        try {
            $currencySymbol = $user->currency?->symbol ?? '€';
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Dashboard currency resolution failed', ['user_id' => $user->id]);
        }

        return Inertia::render('Dashboard', [
            'invoice_stats' => $invoiceStats,
            'currency_symbol' => $currencySymbol,
        ]);
    }
}
