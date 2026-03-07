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

        return Inertia::render('Dashboard', [
            'invoice_stats' => $this->invoiceService->getInvoiceStats($user->id),
            'currency_symbol' => $user->currency?->symbol ?? '€',
        ]);
    }
}
