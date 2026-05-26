<?php

namespace App\Http\Controllers;

use App\Contracts\AutomatizationServiceInterface;
use App\Contracts\InvoiceServiceInterface;
use App\Contracts\RecipientServiceInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly InvoiceServiceInterface $invoiceService,
        private readonly AutomatizationServiceInterface $automatizationService,
        private readonly RecipientServiceInterface $recipientService,
    ) {
    }

    public function index(Request $request): Response
    {
        $user = $request->user();

        $invoiceStats = $this->invoiceService->getInvoiceStats($user->id);

        $currencySymbol = $user->currency?->symbol ?? '€';

        return Inertia::render('Dashboard', [
            'invoice_stats' => $invoiceStats,
            'currency_symbol' => $currencySymbol,
            'automatizations' => $this->automatizationService->listForUser($user->id),
            'recipients' => $this->recipientService->listForUser($user->id),
        ]);
    }
}
