<script setup lang="ts">
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import InvoicePreview from './InvoicePreview.vue';
import { DEMO_CLIENTS, DEMO_DASHBOARD, INVOICE_COLORS } from '../Utils/demo';
import { useDemoFormat } from '../Composables/useDemoFormat';

const { t } = useI18n();
const { money, month } = useDemoFormat();

const invoiceColor = ref(INVOICE_COLORS[0].value);

const statusSummary = computed(() => DEMO_DASHBOARD.byStatus
    .map(({ status, count }) => `${t(`invoiceStatus.${status}`)}: ${count}`)
    .join(', '));
</script>

<template>
    <section id="features" class="g-section guest-wrap" aria-labelledby="features-title">
        <div class="g-head" data-head>
            <div>
                <span class="g-eyebrow"><span class="g-dot" />{{ t('welcome.features.eyebrow') }}</span>
                <h2 id="features-title" class="g-h2" data-reveal="lines">{{ t('welcome.features.title') }}</h2>
            </div>
            <p class="g-lead" data-head-lead>{{ t('welcome.features.description') }}</p>
        </div>

        <div class="bento">
            <article class="g-card tile tile--invoice" data-aos="fade-up">
                <div class="tile-text">
                    <h3>{{ t('welcome.features.invoice.title') }}</h3>
                    <p>{{ t('welcome.features.invoice.description') }}</p>
                </div>
                <div class="g-tray tile-push">
                    <InvoicePreview :color="invoiceColor" />
                </div>
            </article>

            <article class="g-card tile tile--clients" data-aos="fade-left" data-aos-delay="100">
                <div class="tile-text">
                    <h3>{{ t('welcome.features.clients.title') }}</h3>
                    <p>{{ t('welcome.features.clients.description') }}</p>
                </div>
                <div class="clients tile-push">
                    <div v-for="client in DEMO_CLIENTS" :key="client.name" class="client">
                        <span class="avatar">{{ client.initials }}</span>
                        <div>
                            <b>{{ client.name }}</b>
                            <small>{{ t('welcome.features.clients.invoiceCount', { count: client.invoices }) }}</small>
                        </div>
                        <span class="g-mono">{{ money(client.turnover, 0) }}</span>
                    </div>
                </div>
            </article>

            <article class="g-card tile tile--brand" data-aos="fade-left" data-aos-delay="200">
                <div class="tile-text">
                    <h3>{{ t('welcome.features.brand.title') }}</h3>
                    <p>{{ t('welcome.features.brand.description') }}</p>
                </div>
                <div class="swatches tile-push" role="group" :aria-label="t('welcome.features.brand.colorsLabel')">
                    <button
                        v-for="color in INVOICE_COLORS"
                        :key="color.key"
                        type="button"
                        class="swatch"
                        :style="{ '--sw': color.value }"
                        :aria-pressed="invoiceColor === color.value"
                        :aria-label="t(`welcome.features.brand.colors.${color.key}`)"
                        :title="t(`welcome.features.brand.colors.${color.key}`)"
                        @click="invoiceColor = color.value"
                    />
                </div>
                <span class="swatch-hint g-mono">invoice_color: {{ invoiceColor }}</span>
            </article>

            <article class="g-card tile tile--dashboard" data-aos="fade-up">
                <div class="tile-text">
                    <h3>{{ t('welcome.features.dashboard.title') }}</h3>
                    <p>{{ t('welcome.features.dashboard.description') }}</p>
                </div>
                <div class="kpis">
                    <div class="kpi">
                        <small>{{ t('welcome.features.dashboard.revenue', { month: month(DEMO_DASHBOARD.month) }) }}</small>
                        <b>{{ money(DEMO_DASHBOARD.revenue, 0) }}</b>
                    </div>
                    <div class="kpi">
                        <small>{{ t('welcome.features.dashboard.awaiting') }}</small>
                        <b>{{ money(DEMO_DASHBOARD.awaiting, 0) }}</b>
                    </div>
                </div>
                <div class="status-chart">
                    <div class="stack" role="img" :aria-label="`${t('welcome.features.dashboard.chartLabel')}: ${statusSummary}`">
                        <i
                            v-for="row in DEMO_DASHBOARD.byStatus"
                            :key="row.status"
                            :class="`stack--${row.status}`"
                            :style="{ flexGrow: row.count }"
                        />
                    </div>
                    <div class="legend">
                        <span v-for="row in DEMO_DASHBOARD.byStatus" :key="row.status" :class="`stack--${row.status}`">
                            {{ t(`invoiceStatus.${row.status}`) }} <b>{{ row.count }}</b>
                        </span>
                    </div>
                </div>
            </article>

            <article class="g-card tile tile--lifecycle" data-aos="fade-up" data-aos-delay="100">
                <div class="tile-text">
                    <h3>{{ t('welcome.features.lifecycle.title') }}</h3>
                    <p>{{ t('welcome.features.lifecycle.description') }}</p>
                </div>
                <div class="flow">
                    <span class="g-status g-status--draft">{{ t('invoiceStatus.draft') }}</span>
                    <span class="flow-line" />
                    <span class="g-status g-status--sent">{{ t('invoiceStatus.sent') }}</span>
                    <span class="flow-line" />
                    <span class="flow-end">
                        <span class="g-status g-status--paid">{{ t('invoiceStatus.paid') }}</span>
                        <span class="g-status g-status--overdue">{{ t('invoiceStatus.overdue') }}</span>
                    </span>
                </div>
                <div class="flow-note">
                    <i class="pi pi-sync" />
                    <span>{{ t('welcome.features.lifecycle.note') }}</span>
                </div>
            </article>
        </div>
    </section>
</template>

<style scoped>
.bento {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 12px;
}
.tile {
    display: flex;
    flex-direction: column;
    gap: 18px;
    min-width: 0;
    padding: 26px;
}
.tile h3 {
    font-size: 1.3rem;
    font-weight: 600;
    letter-spacing: -0.03em;
}
.tile-text {
    display: grid;
    gap: 8px;
}
.tile-text p {
    max-width: 42ch;
    font-size: 15px;
    color: var(--g-muted);
}
.tile-push { margin-top: auto; }
.tile--invoice { grid-column: span 4; grid-row: span 2; }
.tile--clients,
.tile--brand { grid-column: span 2; }
.tile--dashboard,
.tile--lifecycle { grid-column: span 3; }

/* clients */
.clients { display: grid; }
.client {
    display: grid;
    grid-template-columns: 34px minmax(0, 1fr) auto;
    align-items: center;
    gap: 12px;
    padding: 11px 0;
}
.client + .client { border-top: 1px solid var(--g-line); }
.avatar {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    background: var(--g-tray);
    font: 600 12px/1 var(--g-font);
}
.client b {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: 500;
    font-size: 14.5px;
}
.client small {
    font: 400 11.5px/1.3 var(--g-font-data);
    color: var(--g-faint);
}
.client .g-mono { font-size: 13px; }

/* swatches */
.swatches {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.swatch {
    position: relative;
    width: 40px;
    height: 40px;
    border: 0;
    border-radius: 12px;
    background: var(--sw);
    box-shadow: inset 0 0 0 1px rgb(0 0 0 / 0.08);
    cursor: pointer;
    transition: transform 0.2s ease;
}
.swatch:hover { transform: translateY(-2px); }
.swatch[aria-pressed="true"]::after {
    content: "";
    position: absolute;
    inset: -5px;
    border: 2px solid var(--g-ink);
    border-radius: 15px;
}
.swatch-hint {
    font-size: 12px;
    color: var(--g-faint);
}

/* dashboard */
.kpis {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}
.kpi {
    display: grid;
    gap: 4px;
    padding: 14px 16px;
    border-radius: var(--g-r-inner);
    background: var(--g-tray);
}
.kpi small {
    font-size: 12.5px;
    color: var(--g-muted);
}
.kpi b {
    font: 600 1.5rem/1 var(--g-font);
    letter-spacing: -0.035em;
    font-variant-numeric: tabular-nums;
}
.status-chart {
    display: grid;
    gap: 10px;
}
.stack {
    display: flex;
    gap: 3px;
    height: 12px;
    overflow: hidden;
    border-radius: 999px;
}
.stack i {
    display: block;
    border-radius: 3px;
    background: var(--c);
}
.stack--paid    { --c: var(--g-st-paid); }
.stack--sent    { --c: var(--g-st-sent); }
.stack--overdue { --c: var(--g-st-overdue); }
.stack--draft   { --c: var(--g-st-draft); }
.legend {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 14px;
    font-size: 12.5px;
    color: var(--g-muted);
}
.legend span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.legend span::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 3px;
    background: var(--c);
}
.legend b {
    color: var(--g-ink);
    font: 500 12.5px/1 var(--g-font-data);
}

/* lifecycle */
.flow {
    display: grid;
    grid-template-columns: auto 1fr auto 1fr auto;
    align-items: center;
    gap: 8px;
}
.flow-line {
    height: 1px;
    background: repeating-linear-gradient(90deg, var(--g-faint) 0 4px, transparent 4px 8px);
}
.flow-end {
    display: grid;
    gap: 8px;
}
.flow-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    border-radius: var(--g-r-inner);
    background: var(--g-tray);
    font-size: 13.5px;
    color: var(--g-muted);
}
.flow-note i {
    margin-top: 4px;
    font-size: 12px;
    color: var(--g-accent);
}

@media (max-width: 980px) {
    .bento { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .tile--invoice { grid-column: span 2; grid-row: auto; }
    .tile--clients,
    .tile--brand { grid-column: span 1; }
    .tile--dashboard,
    .tile--lifecycle { grid-column: span 2; }
}
@media (max-width: 640px) {
    .bento { grid-template-columns: 1fr; }
    .bento > .tile { grid-column: auto; }
}
@media (max-width: 520px) {
    .flow {
        grid-template-columns: 1fr;
        justify-items: start;
    }
    .flow-line {
        width: 1px;
        height: 14px;
        margin-left: 10px;
        background: var(--g-faint);
    }
}
</style>
