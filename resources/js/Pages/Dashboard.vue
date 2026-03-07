<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceOverviewCards from '@/Components/InvoiceOverviewCards.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    invoice_stats: {
        type: Object,
        default: () => ({ total_invoiced: 0, paid: 0, awaiting: 0, overdue: 0 }),
    },
    currency_symbol: {
        type: String,
        default: '€',
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="mb-6 text-lg font-medium text-gray-900">Dashboard</h1>
            <InvoiceOverviewCards :stats="invoice_stats" :currency-symbol="currency_symbol" />
        </div>
    </AuthenticatedLayout>
</template>
