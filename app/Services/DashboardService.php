<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Automatization;
use App\Models\Invoice;
use App\Models\Recipient;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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
     * @return list<array{
     *   id:int,
     *   number:string|null,
     *   recipient_name:string|null,
     *   total_price:float,
     *   invoice_status:array{id:int,code:string,name:string}|null,
     *   created_at:string|null
     * }>
     */
    public function getRecentInvoices(int $userId, int $limit = 6): array
    {
        /** @var EloquentCollection<int, Invoice> $invoices */
        $invoices = Invoice::forUser($userId)
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
            ]);

        return $invoices->map(fn (Invoice $i): array => [
            'id' => $i->id,
            'number' => $i->number,
            'recipient_name' => $i->recipient_name,
            'total_price' => (float) $i->total_price,
            'invoice_status' => $i->invoiceStatus !== null ? [
                'id' => $i->invoiceStatus->id,
                'code' => $i->invoiceStatus->code,
                'name' => $i->invoiceStatus->name,
            ] : null,
            'created_at' => $i->created_at?->toDateString(),
        ])->values()->all();
    }

    /**
     * @return list<array{
     *   id:int,
     *   type_label:string,
     *   recipient_label:string|null,
     *   date_trigger:string|null,
     *   due_offset_days:int|null,
     *   last_run_at:string|null
     * }>
     */
    public function getActiveAutomatizations(int $userId, int $limit = 6): array
    {
        /** @var EloquentCollection<int, Automatization> $automatizations */
        $automatizations = Automatization::forUser($userId)
            ->with('recipient')
            ->where('is_active', true)
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get([
                'id',
                'type',
                'recipient_id',
                'date_trigger',
                'due_offset_days',
                'last_run_at',
                'updated_at',
            ]);

        return $automatizations->map(fn (Automatization $a): array => [
            'id' => $a->id,
            'type_label' => $a->type->label(),
            'recipient_label' => $a->recipient
                ? ($a->recipient->company_name ?? $a->recipient->name)
                : null,
            'date_trigger' => $a->date_trigger?->toDateString(),
            'due_offset_days' => $a->due_offset_days,
            'last_run_at' => $a->last_run_at?->toDateTimeString(),
        ])->values()->all();
    }
}

