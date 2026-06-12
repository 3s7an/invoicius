<script setup>
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import DashboardPanel from '@/Pages/Dashboard/Components/DashboardPanel.vue';
import { formatAmount, formatDate } from '@/utils/formatters';
import { invoiceStatusSeverity } from '@/Pages/Dashboard/Utils/statusSeverity';

defineProps({
    recent_invoices: {
        type: Array,
        default: () => [],
    },
    currency_symbol: {
        type: String,
        default: '€',
    },
});
</script>

<template>
    <DashboardPanel title="Posledné faktúry">
        <template #actions>
            <Link :href="route('invoices')">
                <Button label="Zobraziť všetky" icon="pi pi-arrow-right" iconPos="right" link size="small" />
            </Link>
        </template>

        <div
            v-if="recent_invoices.length === 0"
            class="flex flex-col items-center justify-center px-5 py-10 text-center sm:px-6"
        >
            <i class="pi pi-inbox mb-3 text-3xl text-gray-300" aria-hidden="true" />
            <p class="text-sm text-gray-600">Zatiaľ tu nie sú žiadne faktúry.</p>
            <Link :href="route('invoices.create')" class="mt-4">
                <Button label="Vytvoriť faktúru" icon="pi pi-plus" size="small" />
            </Link>
        </div>

        <template v-else>
            <ul class="divide-y divide-gray-200 px-5 sm:px-6 md:hidden">
                <li
                    v-for="invoice in recent_invoices"
                    :key="invoice.id"
                    class="py-3 first:pt-4 last:pb-4"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-gray-800">{{ invoice.number || '—' }}</p>
                            <p class="mt-0.5 truncate text-xs text-gray-500">
                                {{ invoice.recipient_name || '—' }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ invoice.created_at ? formatDate(invoice.created_at) : '—' }}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <Tag
                                v-if="invoice.status_name"
                                :value="invoice.status_name"
                                :severity="invoiceStatusSeverity(invoice.status_name)"
                                rounded
                            />
                            <p class="mt-1.5 text-sm font-medium tabular-nums text-gray-800">
                                {{ formatAmount(invoice.total_price) }} {{ currency_symbol }}
                            </p>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="hidden overflow-hidden md:block">
                <DataTable
                    :value="recent_invoices"
                    size="small"
                    class="text-sm"
                >
                    <Column field="number" header="Číslo">
                        <template #body="{ data }">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-gray-800">{{ data.number || '—' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ data.created_at ? formatDate(data.created_at) : '—' }}
                                </p>
                            </div>
                        </template>
                    </Column>
                    <Column field="recipient_name" header="Klient">
                        <template #body="{ data }">
                            <span class="block truncate">{{ data.recipient_name || '—' }}</span>
                        </template>
                    </Column>
                    <Column field="status_name" header="Stav">
                        <template #body="{ data }">
                            <Tag
                                v-if="data.status_name"
                                :value="data.status_name"
                                :severity="invoiceStatusSeverity(data.status_name)"
                                rounded
                            />
                            <span v-else class="text-gray-400">—</span>
                        </template>
                    </Column>
                    <Column field="total_price" header="Suma" class="text-right">
                        <template #body="{ data }">
                            <span class="font-medium tabular-nums text-gray-800">
                                {{ formatAmount(data.total_price) }} {{ currency_symbol }}
                            </span>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </template>
    </DashboardPanel>
</template>
