<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Automatization;
use App\Models\Invoice;
use App\Models\Recipient;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * @return array{
     *   invoices:int,
     *   clients:int,
     *   automatizations_active:int
     * }
     */
    public function getCounts(int $userId): array
    {
        return [
            'invoices' => Invoice::forUser($userId)->count(),
            'clients' => Recipient::forUser($userId)->count(),
            'automatizations_active' => Automatization::forUser($userId)->where('is_active', true)->count(),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{
     *   id:int,
     *   number:string|null,
     *   recipient_name:string|null,
     *   total_price:float,
     *   status_name:string|null,
     *   created_at:string|null
     * }>
     */
    public function getRecentInvoices(int $userId, int $limit = 6): Collection
    {
        return Invoice::forUser($userId)
            ->with('invoiceStatus')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get([
                'id',
                'number',
                'recipient_name',
                'total_price',
                'invoice_status_id',
                'created_at',
            ])
            ->map(fn ($i) => [
                'id' => $i->id,
                'number' => $i->number,
                'recipient_name' => $i->recipient_name,
                'total_price' => (float) $i->total_price,
                'status_name' => $i->invoiceStatus?->name,
                'created_at' => optional($i->created_at)->toDateString(),
            ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{
     *   id:int,
     *   name:string,
     *   recipient_label:string|null,
     *   date_trigger:string|null,
     *   due_offset_days:int|null,
     *   last_run_at:string|null
     * }>
     */
    public function getActiveAutomatizations(int $userId, int $limit = 6): Collection
    {
        return Automatization::forUser($userId)
            ->with('recipient')
            ->where('is_active', true)
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get([
                'id',
                'name',
                'recipient_id',
                'date_trigger',
                'due_offset_days',
                'last_run_at',
                'updated_at',
            ])
            ->map(fn ($a) => [
                'id' => $a->id,
                'name' => $a->name,
                'recipient_label' => $a->recipient
                    ? ($a->recipient->company_name ?? $a->recipient->name)
                    : null,
                'date_trigger' => optional($a->date_trigger)->toDateString(),
                'due_offset_days' => $a->due_offset_days,
                'last_run_at' => optional($a->last_run_at)?->toDateTimeString(),
            ]);
    }
}

