<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceStatsPie from '@/Components/InvoiceStatsPie.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { formatAmount, formatDate } from '@/utils/formatters';

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
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="app-page-title">Prehľad</h1>
                </div>
            </div>

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
                            <p class="text-sm font-medium text-gray-500">Faktúry</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ counts.invoices }}</p>
                            <p class="mt-1 text-xs text-gray-500">celkom v systéme</p>
                        </div>
                        <div class="flex-1 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Klienti</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ counts.clients }}</p>
                            <p class="mt-1 text-xs text-gray-500">aktívna databáza</p>
                        </div>
                        <div class="flex-1 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Automatizácie</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ counts.automatizations_active }}</p>
                            <p class="mt-1 text-xs text-gray-500">aktívne</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">Posledné faktúry</h2>
                        </div>
                        <Link
                            :href="route('invoices')"
                            class="text-sm font-semibold text-emerald-700 hover:text-emerald-800"
                        >
                            Zobraziť všetky
                        </Link>
                    </div>

                    <div
                        v-if="recent_invoices.length === 0"
                        class="mt-4 rounded-xl bg-gray-50 p-6 text-sm text-gray-600"
                    >
                        Zatiaľ tu nie sú žiadne faktúry.
                    </div>

                    <div v-else class="mt-4 overflow-hidden rounded-xl ring-1 ring-gray-200">
                        <div class="grid grid-cols-12 gap-0 bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <div class="col-span-6">Číslo</div>
                            <div class="col-span-4">Klient / Stav</div>
                            <div class="col-span-2 text-right">Suma</div>
                        </div>

                        <div class="divide-y divide-gray-100 bg-white">
                            <div
                                v-for="inv in recent_invoices"
                                :key="inv.id"
                                class="grid grid-cols-12 items-center gap-0 px-4 py-3"
                            >
                                <div class="col-span-6 min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-900">{{ inv.number }}</p>
                                    <p class="truncate text-xs text-gray-500">
                                        {{ inv.created_at ? formatDate(inv.created_at) : '—' }}
                                    </p>
                                </div>
                                <div class="col-span-4 min-w-0">
                                    <p class="truncate text-sm text-gray-900">{{ inv.recipient_name || '—' }}</p>
                                    <p class="truncate text-xs text-gray-500">{{ inv.status_name || '—' }}</p>
                                </div>
                                <div class="col-span-2 text-right text-sm font-semibold text-gray-900">
                                    {{ formatAmount(inv.total_price) }} {{ currency_symbol }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-base font-semibold text-gray-900">Aktívne automatizácie</h2>
                        <Link
                            :href="route('automatizations.index')"
                            class="text-sm font-semibold text-emerald-700 hover:text-emerald-800"
                        >
                            Spravovať
                        </Link>
                    </div>

                    <div
                        v-if="active_automatizations.length === 0"
                        class="mt-4 rounded-xl bg-gray-50 p-6 text-sm text-gray-600"
                    >
                        Zatiaľ nemáš žiadne aktívne automatizácie.
                    </div>

                    <div v-else class="mt-4 space-y-3">
                        <div
                            v-for="a in active_automatizations"
                            :key="a.id"
                            class="rounded-xl bg-gray-50/60 p-4 ring-1 ring-gray-200/60"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <p class="text-sm font-semibold text-gray-900">{{ a.type }}</p>
                                <p class="text-xs text-gray-500">next: {{ a.date_trigger || '—' }}</p>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ a.recipient_label ? `Klient: ${a.recipient_label}` : 'Pre všetkých klientov' }}
                                <span v-if="a.due_offset_days != null"> · offset: {{ a.due_offset_days }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
