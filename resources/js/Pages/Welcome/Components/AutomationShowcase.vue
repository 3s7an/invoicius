<script setup lang="ts">
import { computed, nextTick, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import type { InvoiceStatusCodeValue } from '@/types';
import { useDemoFormat } from '../Composables/useDemoFormat';

const { t } = useI18n();
const { money, month, date } = useDemoFormat();

type AutomationKey = 'gen' | 'report' | 'due' | 'status';

interface LogRow {
    at: Date
    text: string
    status: InvoiceStatusCodeValue
    label: string
}

const AUTOMATIONS: { key: AutomationKey; icon: string }[] = [
    { key: 'gen', icon: 'pi-file' },
    { key: 'report', icon: 'pi-chart-bar' },
    { key: 'due', icon: 'pi-bell' },
    { key: 'status', icon: 'pi-sync' },
];

const active = ref<AutomationKey>('gen');
const tabRefs = ref<HTMLButtonElement[]>([]);

const chips = computed<Record<AutomationKey, { icon: string; text: string }[]>>(() => ({
    gen: [
        { icon: 'pi-clock', text: t('welcome.automations.chips.monthlyFirst') },
        { icon: 'pi-users', text: 'Kovomont a.s.' },
        { icon: 'pi-file', text: t('welcome.invoicePreview.items.hosting') },
    ],
    report: [
        { icon: 'pi-clock', text: t('welcome.automations.chips.monthlyFirst') },
        { icon: 'pi-envelope', text: t('welcome.automations.chips.reportEmail') },
    ],
    due: [
        { icon: 'pi-clock', text: t('welcome.automations.chips.daily') },
        { icon: 'pi-bell', text: t('welcome.automations.chips.daysBefore', { days: 3 }) },
    ],
    status: [
        { icon: 'pi-clock', text: t('welcome.automations.chips.daily') },
        { icon: 'pi-sync', text: t('welcome.automations.chips.statusChange') },
    ],
}));

const logs = computed<Record<AutomationKey, LogRow[]>>(() => {
    const created = (number: string) => t('welcome.automations.log.created', { number, client: 'Kovomont a.s.' });
    const report = (monthIndex: number, value: number) =>
        t('welcome.automations.log.report', { month: month(new Date(2026, monthIndex, 1)), amount: money(value, 0) });
    const invoice = (number: string, client: string) => t('welcome.automations.log.invoice', { number, client });

    return {
        gen: [
            { at: new Date(2026, 5, 1), text: created('2026-0039'), status: 'sent', label: t('invoiceStatus.sent') },
            { at: new Date(2026, 4, 1), text: created('2026-0031'), status: 'paid', label: t('invoiceStatus.paid') },
            { at: new Date(2026, 3, 1), text: created('2026-0024'), status: 'paid', label: t('invoiceStatus.paid') },
        ],
        report: [
            { at: new Date(2026, 5, 1), text: report(4, 8120), status: 'paid', label: t('welcome.automations.log.delivered') },
            { at: new Date(2026, 4, 1), text: report(3, 6980), status: 'paid', label: t('welcome.automations.log.delivered') },
        ],
        due: [
            { at: new Date(2026, 5, 25), text: invoice('2026-0041', 'Bistro Lipa'), status: 'sent', label: t('welcome.automations.log.sent') },
            { at: new Date(2026, 5, 20), text: invoice('2026-0038', 'Studio Nord'), status: 'sent', label: t('welcome.automations.log.sent') },
        ],
        status: [
            { at: new Date(2026, 5, 29), text: invoice('2026-0040', 'Tatra Build'), status: 'overdue', label: t('invoiceStatus.overdue') },
            { at: new Date(2026, 5, 16), text: invoice('2026-0036', 'Pekáreň Zora'), status: 'overdue', label: t('invoiceStatus.overdue') },
        ],
    };
});

const ARROW_STEPS: Record<string, number> = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 };

function onKeydown(event: KeyboardEvent, index: number) {
    const step = ARROW_STEPS[event.key];
    if (!step) return;

    event.preventDefault();
    const next = (index + step + AUTOMATIONS.length) % AUTOMATIONS.length;
    active.value = AUTOMATIONS[next].key;
    nextTick(() => tabRefs.value[next]?.focus());
}
</script>

<template>
    <section id="automations" class="g-section guest-wrap" aria-labelledby="automations-title">
        <div class="g-head" data-head>
            <div>
                <span class="g-eyebrow"><span class="g-dot" />{{ t('welcome.automations.eyebrow') }}</span>
                <h2 id="automations-title" class="g-h2" data-reveal="lines">{{ t('welcome.automations.title') }}</h2>
            </div>
            <p class="g-lead" data-head-lead>{{ t('welcome.automations.description') }}</p>
        </div>

        <div class="auto">
            <div
                class="auto-list"
                role="tablist"
                :aria-label="t('welcome.automations.tabsLabel')"
                data-aos="fade-right"
            >
                <button
                    v-for="(automation, index) in AUTOMATIONS"
                    :id="`automation-tab-${automation.key}`"
                    :key="automation.key"
                    ref="tabRefs"
                    type="button"
                    role="tab"
                    class="auto-tab"
                    aria-controls="automation-panel"
                    :aria-selected="active === automation.key"
                    :tabindex="active === automation.key ? 0 : -1"
                    @click="active = automation.key"
                    @keydown="onKeydown($event, index)"
                >
                    <span class="auto-icon"><i class="pi" :class="automation.icon" /></span>
                    <span>
                        <b>{{ t(`welcome.automations.types.${automation.key}.label`) }}</b>
                        <small>{{ t(`welcome.automations.types.${automation.key}.short`) }}</small>
                    </span>
                </button>
            </div>

            <div class="g-tray" data-aos="fade-left" data-aos-delay="100">
                <div
                    id="automation-panel"
                    class="g-card auto-panel"
                    role="tabpanel"
                    :aria-labelledby="`automation-tab-${active}`"
                >
                    <div class="panel-text">
                        <h3>{{ t(`welcome.automations.types.${active}.label`) }}</h3>
                        <p>{{ t(`welcome.automations.types.${active}.description`) }}</p>
                    </div>

                    <div class="chips">
                        <span v-for="chip in chips[active]" :key="chip.text" class="chip">
                            <i class="pi" :class="chip.icon" />{{ chip.text }}
                        </span>
                    </div>

                    <div class="log-wrap">
                        <span class="log-caption">{{ t('welcome.automations.lastRuns') }}</span>
                        <div class="log">
                            <div v-for="row in logs[active]" :key="row.text" class="log-row">
                                <time class="g-mono">{{ date(row.at) }}</time>
                                <span>{{ row.text }}</span>
                                <span class="g-status" :class="`g-status--${row.status}`">{{ row.label }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.auto {
    display: grid;
    grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
    gap: 12px;
}
.auto-list {
    display: grid;
    align-content: start;
    gap: 8px;
}
.auto-tab {
    display: grid;
    grid-template-columns: 40px minmax(0, 1fr);
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    border: 1px solid transparent;
    border-radius: var(--g-r-card);
    background: transparent;
    color: var(--g-ink);
    font: inherit;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.2s ease, border-color 0.2s ease;
}
.auto-tab:hover { background: rgb(255 255 255 / 0.6); }
.auto-tab[aria-selected="true"] {
    background: var(--g-surface);
    border-color: var(--g-line);
    box-shadow: var(--g-shadow);
}
.auto-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: var(--g-tray);
    color: var(--g-muted);
}
.auto-tab[aria-selected="true"] .auto-icon {
    background: var(--g-accent-soft);
    color: var(--g-accent);
}
.auto-tab b {
    display: block;
    font-weight: 500;
    font-size: 15.5px;
}
.auto-tab small {
    font: 400 12px/1.4 var(--g-font-data);
    color: var(--g-faint);
}
.auto-panel {
    display: grid;
    gap: 22px;
    height: 100%;
    padding: 28px;
}
.panel-text {
    display: grid;
    gap: 12px;
}
.panel-text h3 {
    font-size: clamp(1.4rem, 2.4vw, 1.9rem);
    font-weight: 600;
    line-height: 1.1;
    letter-spacing: -0.04em;
}
.panel-text p {
    max-width: 52ch;
    color: var(--g-muted);
}
.chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.chip {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 12px;
    border-radius: 999px;
    background: var(--g-tray);
    font: 500 12.5px/1 var(--g-font-data);
}
.chip i {
    font-size: 11px;
    color: var(--g-accent);
}
.log-wrap {
    display: grid;
    gap: 10px;
}
.log-caption {
    font: 500 11px/1 var(--g-font-data);
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--g-faint);
}
.log {
    display: grid;
    border-top: 1px solid var(--g-line);
}
.log-row {
    display: grid;
    grid-template-columns: 120px minmax(0, 1fr) auto;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid var(--g-line);
    font-size: 14px;
}
.log-row time {
    font-size: 12px;
    color: var(--g-faint);
}
@media (max-width: 860px) {
    .auto { grid-template-columns: 1fr; }
    .auto-list {
        grid-auto-flow: column;
        grid-auto-columns: minmax(240px, 1fr);
        overflow-x: auto;
        padding-bottom: 4px;
    }
}
@media (max-width: 520px) {
    .log-row { grid-template-columns: 1fr auto; }
    .log-row time { grid-column: 1 / -1; }
}
</style>
