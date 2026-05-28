<script setup>
import { Link } from '@inertiajs/vue3';
import { formatAmount, formatDate } from '@/utils/formatters';

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
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Posledné faktúry</h2>
            </div>
            <Link :href="route('invoices')" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                Zobraziť všetky
            </Link>
        </div>

        <div v-if="recent_invoices.length === 0" class="mt-4 rounded-xl bg-gray-50 p-6 text-sm text-gray-600">
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
</template>

