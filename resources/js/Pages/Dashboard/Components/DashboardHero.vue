<script setup lang="ts">
import Card from 'primevue/card';
import InvoiceStatsPie from '@/Components/InvoiceStatsPie.vue';
import type { InvoiceStats, DashboardCounts } from '../Utils/types';

interface StatCard {
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

const statCards: StatCard[] = [
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
        label: 'Automatizácie',
        hint: 'aktívne',
        icon: 'pi pi-bolt',
        iconClass: 'text-amber-600 bg-amber-50',
        value: (counts) => counts.automatizations_active,
    },
];
</script>

<template>
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-3">
            <Card
                v-for="card in statCards"
                :key="card.key"
                class="shadow-sm"
            >
                <template #content>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ card.label }}</p>
                            <p class="mt-2 text-2xl font-semibold tabular-nums text-gray-900">
                                {{ card.value(counts) }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">{{ card.hint }}</p>
                        </div>
                        <span
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                            :class="card.iconClass"
                        >
                            <i :class="[card.icon, 'text-lg']" aria-hidden="true" />
                        </span>
                    </div>
                </template>
            </Card>
        </div>

        <InvoiceStatsPie
            :stats="invoice_stats"
            :currency-symbol="currency_symbol"
        />
    </div>
</template>
