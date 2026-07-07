<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { formatAmount } from '@/utils/formatters';

const { t } = useI18n();

const props = defineProps<{
    stats: { paid: number; awaiting: number; overdue: number; draft: number }
    currencySymbol: string
}>();

const segments = computed(() => {
    const paid = Number(props.stats?.paid ?? 0);
    const awaiting = Number(props.stats?.awaiting ?? 0);
    const overdue = Number(props.stats?.overdue ?? 0);
    const draft = Number(props.stats?.draft ?? 0);
    return [
        { key: 'paid', label: t('dashboard.pie.paid'), value: paid, color: 'var(--status-paid)' },
        { key: 'awaiting', label: t('dashboard.pie.awaiting'), value: awaiting, color: 'var(--status-awaiting)' },
        { key: 'overdue', label: t('dashboard.pie.overdue'), value: overdue, color: 'var(--status-overdue)' },
        { key: 'draft', label: t('dashboard.pie.draft'), value: draft, color: 'var(--status-draft)' },
    ];
});

const total = computed(() => segments.value.reduce((s, x) => s + x.value, 0));
const hasAny = computed(() => total.value > 0);

const donutGradient = computed(() => {
    if (!hasAny.value) return 'conic-gradient(#e5e7eb 0deg 360deg)';
    const t = total.value;
    let acc = 0;
    const stops = segments.value
        .filter((s) => s.value > 0)
        .map((s) => {
            const start = (acc / t) * 360;
            acc += s.value;
            return `${s.color} ${start}deg ${(acc / t) * 360}deg`;
        });
    return `conic-gradient(${stops.join(', ')})`;
});
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-3.5">
            <h2 class="text-sm font-semibold text-gray-900">{{ t('dashboard.pie.title') }}</h2>
            <p class="mt-0.5 text-xs text-gray-500">{{ t('dashboard.pie.subtitle') }}</p>
        </div>

        <div class="p-5">
            <div v-if="!hasAny" class="rounded-xl bg-gray-50 p-6 text-sm text-gray-500">
                {{ t('dashboard.pie.empty') }}
            </div>

            <div v-else style="display:flex; align-items:center; gap:40px; flex-wrap:wrap;">
                <!-- CSS conic-gradient donut -->
                <div
                    style="position:relative; width:168px; height:168px; flex-shrink:0; border-radius:50%;"
                    :style="{ background: donutGradient }"
                >
                    <div style="position:absolute; inset:17px; border-radius:50%; background:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:4px;">
                        <span style="font-size:17px; font-weight:600; color:var(--text-strong); font-variant-numeric:tabular-nums; white-space:nowrap; line-height:1.2;">
                            {{ formatAmount(total) }} {{ currencySymbol }}
                        </span>
                        <span style="font-size:12px; color:var(--text-muted); margin-top:2px;">{{ t('dashboard.pie.invoiced') }}</span>
                    </div>
                </div>

                <!-- Legend -->
                <div style="display:flex; flex-direction:column; gap:14px; flex:1; min-width:200px;">
                    <div v-for="s in segments" :key="s.key" style="display:flex; align-items:center; gap:10px;">
                        <span
                            style="width:10px; height:10px; border-radius:9999px; flex-shrink:0;"
                            :style="{ background: s.color }"
                        />
                        <span style="font-size:14px; color:var(--text-body); flex:1;">{{ s.label }}</span>
                        <span style="font-size:14px; font-weight:600; color:var(--text-strong); font-variant-numeric:tabular-nums; white-space:nowrap;">
                            {{ formatAmount(s.value) }} {{ currencySymbol }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
