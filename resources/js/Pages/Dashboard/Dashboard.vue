<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import DashboardHero from '@/Pages/Dashboard/Components/DashboardHero.vue';
import DashboardRecentInvoices from '@/Pages/Dashboard/Components/DashboardRecentInvoices.vue';
import DashboardActiveAutomatizations from '@/Pages/Dashboard/Components/DashboardActiveAutomatizations.vue';
import type { AuthUser } from '@/types/inertia';
import type { InvoiceStats, DashboardCounts, ActiveAutomatization, DashboardRecentInvoice } from './Utils/types';

defineProps<{
    invoice_stats: InvoiceStats
    currency_symbol: string
    counts: DashboardCounts
    recent_invoices: DashboardRecentInvoice[]
    active_automatizations: ActiveAutomatization[]
}>();

const userName = computed(() => {
    const user = (usePage().props as { auth?: { user: AuthUser } }).auth?.user;
    return user?.company_name || user?.name || null;
});
</script>

<template>
    <Head title="Prehľad" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <PageHeader title="Prehľad">
                <template v-if="userName" #subtitle>
                    Vitaj späť, {{ userName }}.
                </template>
            </PageHeader>

            <DashboardHero
                :invoice_stats="invoice_stats"
                :currency_symbol="currency_symbol"
                :counts="counts"
            />

            <div class="grid gap-6 xl:grid-cols-2">
                <DashboardRecentInvoices
                    :recent_invoices="recent_invoices"
                    :currency_symbol="currency_symbol"
                />
                <DashboardActiveAutomatizations :active_automatizations="active_automatizations" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
