<script setup>
import InvoiceStatsPie from '@/Components/InvoiceStatsPie.vue';

defineProps({
    invoice_stats: {
        type: Object,
        default: () => ({ total_invoiced: 0, paid: 0, awaiting: 0, overdue: 0 }),
    },
    currency_symbol: {
        type: String,
        default: '€',
    },
    counts: {
        type: Object,
        default: () => ({ invoices: 0, clients: 0, automatizations_active: 0 }),
    },
});
</script>

<template>
    <div class="grid gap-6 lg:grid-cols-12 lg:items-stretch">
        <div class="lg:col-span-6">
            <InvoiceStatsPie
                class="h-full min-h-[380px]"
                :stats="invoice_stats"
                :currency-symbol="currency_symbol"
            />
        </div>

        <div class="space-y-6 lg:col-span-6">
            <div class="flex h-full min-h-[380px] flex-col gap-4 sm:flex-row lg:flex-col">
                <div class="flex-1 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-base font-semibold text-gray-600">Faktúry</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ counts.invoices }}</p>
                    <p class="mt-1 text-xs text-gray-500">celkom v systéme</p>
                </div>
                <div class="flex-1 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-base font-semibold text-gray-600">Klienti</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ counts.clients }}</p>
                    <p class="mt-1 text-xs text-gray-500">aktívna databáza</p>
                </div>
                <div class="flex-1 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-base font-semibold text-gray-600">Automatizácie</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ counts.automatizations_active }}</p>
                    <p class="mt-1 text-xs text-gray-500">aktívne</p>
                </div>
            </div>
        </div>
    </div>
</template>

