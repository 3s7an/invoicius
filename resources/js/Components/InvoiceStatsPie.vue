<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Chart, registerables } from 'chart.js';
import { formatAmount } from '@/utils/formatters';

Chart.register(...registerables);

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({ paid: 0, awaiting: 0, overdue: 0 }),
    },
    currencySymbol: {
        type: String,
        default: '€',
    },
});

const canvasRef = ref(null);
let chart = null;

const dataValues = computed(() => {
    const paid = Number(props.stats?.paid ?? 0);
    const awaiting = Number(props.stats?.awaiting ?? 0);
    const overdue = Number(props.stats?.overdue ?? 0);
    return { paid, awaiting, overdue };
});

const total = computed(() => dataValues.value.paid + dataValues.value.awaiting + dataValues.value.overdue);
const hasAny = computed(() => total.value > 0);

const segments = computed(() => {
    const { paid, awaiting, overdue } = dataValues.value;
    const t = total.value || 0;

    const items = [
        { key: 'paid', label: 'Uhradené', value: paid, color: '#06b6d4', ring: 'ring-cyan-200', bg: 'bg-cyan-500' },
        { key: 'awaiting', label: 'Čaká na úhradu', value: awaiting, color: '#f59e0b', ring: 'ring-amber-200', bg: 'bg-amber-500' },
        { key: 'overdue', label: 'Po splatnosti', value: overdue, color: '#ef4444', ring: 'ring-red-200', bg: 'bg-red-500' },
    ];

    return items.map((i) => ({
        ...i,
        pct: t > 0 ? Math.round((i.value / t) * 100) : 0,
    }));
});

function destroyChart() {
    if (chart) {
        chart.destroy();
        chart = null;
    }
}

function renderChart() {
    if (!canvasRef.value) return;
    destroyChart();

    const { paid, awaiting, overdue } = dataValues.value;

    const centerTextPlugin = {
        id: 'centerText',
        afterDraw(c) {
            const { ctx } = c;
            const meta = c.getDatasetMeta(0);
            if (!meta?.data?.length) return;

            const x = meta.data[0].x;
            const y = meta.data[0].y;

            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#111827';

            ctx.font = '600 18px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial';
            ctx.fillText(`${formatAmount(total.value)} ${props.currencySymbol}`, x, y - 8);

            ctx.fillStyle = '#6b7280';
            ctx.font = '500 12px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial';
            ctx.fillText('spolu', x, y + 14);
            ctx.restore();
        },
    };

    chart = new Chart(canvasRef.value.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Uhradené', 'Čaká na úhradu', 'Po splatnosti'],
            datasets: [
                {
                    data: [paid, awaiting, overdue],
                    backgroundColor: segments.value.map((s) => s.color),
                    borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                    borderWidth: 3,
                    borderRadius: 10,
                    spacing: 2,
                    hoverOffset: 8,
                },
            ],
        },
        plugins: [centerTextPlugin],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => {
                            const value = Number(ctx.raw ?? 0);
                            const pct = total.value > 0 ? Math.round((value / total.value) * 100) : 0;
                            return `${ctx.label}: ${formatAmount(value)} ${props.currencySymbol} (${pct}%)`;
                        },
                    },
                },
            },
            cutout: '72%',
        },
    });
}

onMounted(renderChart);
onBeforeUnmount(destroyChart);

watch(
    () => [dataValues.value.paid, dataValues.value.awaiting, dataValues.value.overdue, props.currencySymbol],
    renderChart
);
</script>

<template>
    <div class="h-full rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Rozdelenie faktúr</h2>
                <p class="mt-1 text-sm text-gray-500">Prehľad podľa stavu faktúr:</p>
            </div>
        </div>

        <div class="mt-5">
            <div v-if="!hasAny" class="rounded-xl bg-gray-50 p-6 text-sm text-gray-600">
                Zatiaľ nemáš žiadne sumy na rozdelenie.
            </div>
            <div v-else class="grid gap-6 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-7">
                    <div class="mx-auto h-64 max-w-[520px]">
                        <canvas ref="canvasRef" />
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="space-y-3">
                        <div
                            v-for="s in segments"
                            :key="s.key"
                            class="flex items-center justify-between gap-4 rounded-xl bg-gray-50/60 p-4 ring-1 ring-gray-200/60"
                        >
                            <div class="flex items-center gap-3">
                                <span class="h-2.5 w-2.5 rounded-full" :class="s.bg" aria-hidden="true" />
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-900">{{ s.label }}</p>
                                    <p class="text-xs text-gray-500">{{ s.pct }}%</p>
                                </div>
                            </div>

                            <p class="shrink-0 text-sm font-semibold text-gray-900">
                                {{ formatAmount(s.value) }} {{ currencySymbol }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

