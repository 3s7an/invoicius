<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Chart, registerables } from 'chart.js';
import { formatAmount } from '@/utils/formatters';

Chart.register(...registerables);

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({ paid: 0, awaiting: 0, overdue: 0, draft: 0 }),
    },
    currencySymbol: {
        type: String,
        default: '€',
    },
});

const canvasRef = ref(null);
const chartContainerRef = ref(null);
let chart = null;
let resizeObserver = null;

const dataValues = computed(() => {
    const paid = Number(props.stats?.paid ?? 0);
    const awaiting = Number(props.stats?.awaiting ?? 0);
    const overdue = Number(props.stats?.overdue ?? 0);
    const draft = Number(props.stats?.draft ?? 0);
    return { paid, awaiting, overdue, draft };
});

const total = computed(
    () =>
        dataValues.value.paid +
        dataValues.value.awaiting +
        dataValues.value.overdue +
        dataValues.value.draft
);
const hasAny = computed(() => total.value > 0);

const segments = computed(() => {
    const { paid, awaiting, overdue, draft } = dataValues.value;
    const t = total.value || 0;

    const items = [
        { key: 'paid', label: 'Uhradené', value: paid, color: '#06b6d4', bg: 'bg-cyan-500' },
        { key: 'awaiting', label: 'Čaká na úhradu', value: awaiting, color: '#f59e0b', bg: 'bg-amber-500' },
        { key: 'overdue', label: 'Po splatnosti', value: overdue, color: '#ef4444', bg: 'bg-red-500' },
        { key: 'draft', label: 'Koncept', value: draft, color: '#94a3b8', bg: 'bg-slate-400' },
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

    const { paid, awaiting, overdue, draft } = dataValues.value;

    const centerTextPlugin = {
        id: 'centerText',
        afterDraw(c) {
            const { ctx } = c;
            const meta = c.getDatasetMeta(0);
            if (!meta?.data?.length) return;

            const x = meta.data[0].x;
            const y = meta.data[0].y;
            const amountSize = Math.min(18, Math.max(13, c.width / 14));
            const labelSize = Math.min(12, Math.max(10, c.width / 22));

            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#111827';

            ctx.font = `500 ${amountSize}px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial`;
            ctx.fillText(`${formatAmount(total.value)} ${props.currencySymbol}`, x, y - 8);

            ctx.fillStyle = '#6b7280';
            ctx.font = `500 ${labelSize}px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial`;
            ctx.fillText('spolu', x, y + 14);
            ctx.restore();
        },
    };

    chart = new Chart(canvasRef.value.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Uhradené', 'Čaká na úhradu', 'Po splatnosti', 'Koncept'],
            datasets: [
                {
                    data: [paid, awaiting, overdue, draft],
                    backgroundColor: segments.value.map((s) => s.color),
                    borderColor: ['#ffffff', '#ffffff', '#ffffff', '#ffffff'],
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
            maintainAspectRatio: true,
            aspectRatio: 1,
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

onMounted(() => {
    renderChart();

    if (chartContainerRef.value) {
        resizeObserver = new ResizeObserver(() => {
            chart?.resize();
        });
        resizeObserver.observe(chartContainerRef.value);
    }
});

onBeforeUnmount(() => {
    resizeObserver?.disconnect();
    destroyChart();
});

watch(
    () => [
        dataValues.value.paid,
        dataValues.value.awaiting,
        dataValues.value.overdue,
        dataValues.value.draft,
        props.currencySymbol,
    ],
    renderChart
);
</script>

<template>
    <div class="h-full overflow-hidden rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
        <div class="border-b border-gray-100 pb-4">
            <h2 class="text-sm font-medium text-gray-800">Rozdelenie faktúr</h2>
            <p class="mt-1 text-sm text-gray-600">Podiel podľa stavu</p>
        </div>

        <div class="mt-5">
            <div v-if="!hasAny" class="rounded-xl bg-gray-50 p-6 text-sm text-gray-600">
                Zatiaľ nemáš žiadne sumy na rozdelenie.
            </div>

            <div
                v-else
                class="flex flex-col items-center gap-6 lg:grid lg:grid-cols-12 lg:items-center lg:gap-8"
            >
                <div class="w-full min-w-0 lg:col-span-7">
                    <div
                        ref="chartContainerRef"
                        class="relative mx-auto aspect-square w-full max-w-[200px] sm:max-w-[260px] md:max-w-[320px] lg:max-w-[400px]"
                    >
                        <canvas ref="canvasRef" class="max-h-full max-w-full" />
                    </div>
                </div>

                <div class="w-full min-w-0 lg:col-span-5">
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-1 lg:gap-0 lg:space-y-4">
                        <div
                            v-for="s in segments"
                            :key="s.key"
                            class="flex items-start gap-2.5 sm:gap-3"
                        >
                            <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full" :class="s.bg" aria-hidden="true" />
                            <div class="min-w-0">
                                <p class="text-sm text-gray-700">{{ s.label }}</p>
                                <p class="mt-0.5 text-sm tabular-nums text-gray-900">
                                    {{ formatAmount(s.value) }} {{ currencySymbol }}
                                </p>
                                <p class="text-xs text-gray-500">{{ s.pct }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
