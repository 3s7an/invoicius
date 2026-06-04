<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashboardHero from '@/Pages/Dashboard/Components/DashboardHero.vue';
import DashboardRecentInvoices from '@/Pages/Dashboard/Components/DashboardRecentInvoices.vue';
import DashboardActiveAutomatizations from '@/Pages/Dashboard/Components/DashboardActiveAutomatizations.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    invoice_stats: {
        type: Object,
        default: () => ({ total_invoiced: 0, paid: 0, awaiting: 0, overdue: 0, draft: 0 }),
    },
    currency_symbol: {
        type: String,
        default: '€',
    },
    counts: {
        type: Object,
        default: () => ({ invoices: 0, clients: 0, automatizations_active: 0 }),
    },
    recent_invoices: {
        type: Array,
        default: () => [],
    },
    active_automatizations: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Prehľad" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <PageHeader title="Prehľad" />

            <DashboardHero :invoice_stats="invoice_stats" :currency_symbol="currency_symbol" :counts="counts" />

            <div class="grid gap-6 lg:grid-cols-2">
                <DashboardRecentInvoices :recent_invoices="recent_invoices" :currency_symbol="currency_symbol" />
                <DashboardActiveAutomatizations :active_automatizations="active_automatizations" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
