<script setup>
import { formatAmount } from '@/utils/formatters';

defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_invoiced: 0,
            paid: 0,
            awaiting: 0,
            overdue: 0,
        }),
    },
    currencySymbol: {
        type: String,
        default: '€',
    },
});

const cards = [
    { key: 'total_invoiced', label: 'Invoiced', dotClass: 'bg-indigo-500' },
    { key: 'paid', label: 'Paid', dotClass: 'bg-cyan-500' },
    { key: 'awaiting', label: 'Awaiting payment', dotClass: 'bg-amber-500' },
    { key: 'overdue', label: 'Overdue', dotClass: 'bg-red-500' },
];
</script>

<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div
            v-for="card in cards"
            :key="card.key"
            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
        >
            <span class="h-2.5 w-2.5 shrink-0 rounded-full" :class="card.dotClass" aria-hidden="true" />
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-500">{{ card.label }}</p>
                <p class="mt-0.5 truncate text-xl font-semibold text-gray-900">
                    {{ formatAmount(stats[card.key]) }} {{ currencySymbol }}
                </p>
            </div>
        </div>
    </div>
</template>
