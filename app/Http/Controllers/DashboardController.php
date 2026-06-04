<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly DashboardService $dashboardService,
    ) {
    }

    public function index(Request $request): Response
    {
        $user = $request->user();

        $invoiceStats = $this->invoiceService->getInvoiceStats($user->id);
        $userId = $user->id;

        $counts = $this->dashboardService->getCounts($userId);

        $recentInvoices = $this->dashboardService->getRecentInvoices($userId, 6);
        $activeAutomatizations = $this->dashboardService->getActiveAutomatizations($userId, 6);

        $currencySymbol = $user->currency?->symbol ?? '€';

        return Inertia::render('Dashboard/Dashboard', [
            'invoice_stats' => $invoiceStats,
            'currency_symbol' => $currencySymbol,
            'counts' => $counts,
            'recent_invoices' => $recentInvoices,
            'active_automatizations' => $activeAutomatizations,
        ]);
    }
}
