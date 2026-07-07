<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { formatAmount } from '@/utils/formatters';

const { t } = useI18n();

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

const cards = computed(() => [
    { key: 'total_invoiced', label: t('dashboard.overview.totalInvoiced'), dotClass: 'bg-indigo-500' },
    { key: 'paid', label: t('dashboard.overview.paid'), dotClass: 'bg-cyan-500' },
    { key: 'awaiting', label: t('dashboard.overview.awaiting'), dotClass: 'bg-amber-500' },
    { key: 'overdue', label: t('dashboard.overview.overdue'), dotClass: 'bg-red-500' },
]);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div
            v-for="card in cards"
            :key="card.key"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
        >
            <div class="flex min-w-0 flex-1 items-center gap-2">
                <span
                    class="h-2.5 w-2.5 shrink-0 rounded-full"
                    :class="card.dotClass"
                    aria-hidden="true"
                />
                <p class="text-sm font-medium text-gray-500">{{ card.label }}</p>
            </div>
            <p class="mt-0.5 truncate text-xl font-semibold text-gray-900">
                {{ formatAmount(stats[card.key]) }} {{ currencySymbol }}
            </p>
        </div>
    </div>
</template>
