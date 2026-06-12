<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import DashboardHero from '@/Pages/Dashboard/Components/DashboardHero.vue';
import DashboardRecentInvoices from '@/Pages/Dashboard/Components/DashboardRecentInvoices.vue';
import DashboardActiveAutomatizations from '@/Pages/Dashboard/Components/DashboardActiveAutomatizations.vue';

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

const userName = computed(() => {
    const user = usePage().props.auth?.user;

    return user?.company_name || user?.name || null;
});
</script>

<template>
    <Head title="Prehľad" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <PageHeader title="Prehľad">
                <template v-if="userName" #subtitle>
                    Vitaj späť, {{ userName }}.
                </template>

                <template #actions>
                    <Link :href="route('invoices.create')">
                        <Button label="Nová faktúra" icon="pi pi-plus" class="p-button-sm" />
                    </Link>
                    <Link :href="route('automatizations.create')">
                        <Button
                            label="Nová automatizácia"
                            icon="pi pi-bolt"
                            class="p-button-sm !border !border-gray-200 !bg-white !text-gray-700 shadow-sm hover:!bg-gray-50"
                        />
                    </Link>
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
