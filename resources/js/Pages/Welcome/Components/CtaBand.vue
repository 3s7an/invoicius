<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { DEMO_STACK } from '../Utils/demo';
import { useDemoFormat } from '../Composables/useDemoFormat';

const { t } = useI18n();
const { money } = useDemoFormat();
</script>

<template>
    <section class="g-section guest-wrap" aria-labelledby="cta-title">
        <div class="band" data-aos="fade-up">
            <div>
                <h2 id="cta-title" data-reveal="lines">{{ t('welcome.cta.title') }}<span class="stop">.</span></h2>
                <p>{{ t('welcome.cta.description') }}</p>
                <div class="band-cta">
                    <Link :href="route('register')" class="g-pill">
                        {{ t('welcome.cta.register') }}
                        <span class="g-knob"><i class="pi pi-arrow-up-right" /></span>
                    </Link>
                    <Link :href="route('login')" class="g-ghost">
                        {{ t('welcome.cta.login') }}
                    </Link>
                </div>
            </div>

            <div class="fan" aria-hidden="true" data-stagger>
                <div v-for="invoice in DEMO_STACK" :key="invoice.number" class="fan-card" data-stagger-item>
                    <header>
                        <b class="g-mono">{{ invoice.number }}</b>
                        <span class="g-status" :class="`g-status--${invoice.status}`">
                            {{ t(`invoiceStatus.${invoice.status}`) }}
                        </span>
                    </header>
                    <span class="amount">{{ money(invoice.amount) }}</span>
                    <small>{{ invoice.client }}</small>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.band {
    display: grid;
    grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
    align-items: center;
    gap: 32px;
    padding: clamp(32px, 6vw, 72px);
    overflow: hidden;
    border: 1px solid var(--g-accent-line);
    border-radius: 32px;
    background: var(--g-accent-soft);
}
.band h2 {
    font-size: clamp(2.4rem, 6vw, 4.8rem);
    font-weight: 600;
    line-height: 0.95;
    letter-spacing: -0.055em;
}
.stop { color: var(--g-accent-dot); }
.band p {
    margin-top: 20px;
    max-width: 40ch;
    font-size: 1.05rem;
    color: var(--g-muted);
}
.band-cta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 30px;
}
.band-cta .g-ghost { border-color: var(--g-accent-line); }

.fan {
    position: relative;
    height: 300px;
}
.fan-card {
    position: absolute;
    left: 50%;
    top: 50%;
    display: grid;
    gap: 10px;
    width: min(300px, 80%);
    padding: 18px 20px;
    border: 1px solid var(--g-line);
    border-radius: 18px;
    background: var(--g-surface);
    box-shadow: 0 18px 40px -18px rgb(6 78 59 / 0.28);
    font-size: 13px;
    transition: transform 0.5s cubic-bezier(0.2, 0.7, 0.2, 1);
}
.fan-card header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.fan-card b {
    font: 600 15px/1 var(--g-font-data);
    letter-spacing: -0.02em;
}
.amount {
    font: 600 22px/1 var(--g-font);
    letter-spacing: -0.03em;
    font-variant-numeric: tabular-nums;
}
.fan-card small { color: var(--g-faint); }
.fan-card:nth-child(1) { transform: translate(-50%, -50%) translate(-58px, 26px) rotate(-9deg); }
.fan-card:nth-child(2) { transform: translate(-50%, -50%) translate(46px, 18px) rotate(7deg); }
.fan-card:nth-child(3) { transform: translate(-50%, -50%) translate(0, -22px) rotate(-1deg); }
.band:hover .fan-card:nth-child(1) { transform: translate(-50%, -50%) translate(-92px, 34px) rotate(-13deg); }
.band:hover .fan-card:nth-child(2) { transform: translate(-50%, -50%) translate(82px, 26px) rotate(11deg); }

@media (max-width: 860px) {
    .band { grid-template-columns: 1fr; }
    .fan { height: 240px; }
}
</style>
