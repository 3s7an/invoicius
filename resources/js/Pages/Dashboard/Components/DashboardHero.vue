<script setup lang="ts">
import InvoiceStatsPie from '@/Components/InvoiceStatsPie.vue';
import type { InvoiceStats, DashboardCounts } from '../Utils/types';

interface StatCardDef {
    key: string
    label: string
    hint: string
    icon: string
    iconClass: string
    value: (counts: DashboardCounts) => number
}

defineProps<{
    invoice_stats: InvoiceStats
    currency_symbol: string
    counts: DashboardCounts
}>();

const statCards: StatCardDef[] = [
    {
        key: 'invoices',
        label: 'Faktúry',
        hint: 'celkom v systéme',
        icon: 'pi pi-file',
        iconClass: 'text-sky-600 bg-sky-50',
        value: (counts) => counts.invoices,
    },
    {
        key: 'clients',
        label: 'Klienti',
        hint: 'celkom v systéme',
        icon: 'pi pi-users',
        iconClass: 'text-violet-600 bg-violet-50',
        value: (counts) => counts.clients,
    },
    {
        key: 'automatizations',
        label: 'Aktívne automatizácie',
        hint: 'bežia automaticky',
        icon: 'pi pi-bolt',
        iconClass: 'text-amber-600 bg-amber-50',
        value: (counts) => counts.automatizations_active,
    },
];
</script>

<template>
    <div class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-3">
            <div
                v-for="card in statCards"
                :key="card.key"
                class="overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[13px] font-medium text-gray-500">{{ card.label }}</p>
                        <p class="mt-2 text-[28px] font-bold tabular-nums text-gray-900 leading-none">
                            {{ card.value(counts) }}
                        </p>
                        <p class="mt-1.5 text-xs text-gray-500">{{ card.hint }}</p>
                    </div>
                    <span
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                        :class="card.iconClass"
                    >
                        <i :class="[card.icon, 'text-lg']" aria-hidden="true" />
                    </span>
                </div>
            </div>
        </div>

        <InvoiceStatsPie
            :stats="invoice_stats"
            :currency-symbol="currency_symbol"
        />
    </div>
</template>
