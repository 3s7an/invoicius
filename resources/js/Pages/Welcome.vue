<script setup lang="ts">
import '../../css/guest.css';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useGuestMotion } from '@/composables/useGuestMotion';
import GuestFooter from '@/Components/Guest/GuestFooter.vue';
import GuestNav from '@/Components/Guest/GuestNav.vue';
import AutomationShowcase from './Welcome/Components/AutomationShowcase.vue';
import CtaBand from './Welcome/Components/CtaBand.vue';
import FaqList from './Welcome/Components/FaqList.vue';
import FeatureBento from './Welcome/Components/FeatureBento.vue';
import HeroShowcase from './Welcome/Components/HeroShowcase.vue';

const { t } = useI18n();

const SWAPS = ['clients', 'vat', 'payment', 'recurring', 'overdue'] as const;
const STEPS = ['profile', 'client', 'invoice', 'automation'] as const;

const root = ref<HTMLElement | null>(null);

useGuestMotion(root);
</script>

<template>
    <Head :title="t('welcome.headTitle')" />

    <div ref="root" class="guest">
        <GuestNav section-links />

        <main>
            <HeroShowcase />

            <section class="g-section guest-wrap" aria-labelledby="swap-title">
                <div class="g-head" data-head>
                    <div>
                        <span class="g-eyebrow"><span class="g-dot" />{{ t('welcome.swap.eyebrow') }}</span>
                        <h2 id="swap-title" class="g-h2" data-reveal="lines">{{ t('welcome.swap.title') }}</h2>
                    </div>
                    <p class="g-lead" data-head-lead>{{ t('welcome.swap.description') }}</p>
                </div>

                <ul class="g-card swap">
                    <li
                        v-for="(key, index) in SWAPS"
                        :key="key"
                        class="swap-row"
                        data-aos="fade-up"
                        :data-aos-delay="index * 100"
                    >
                        <span class="was">{{ t(`welcome.swap.items.${key}.was`) }}</span>
                        <span class="arrow" aria-hidden="true"><i class="pi pi-arrow-right" /></span>
                        <span class="now"><i class="pi pi-check" />{{ t(`welcome.swap.items.${key}.now`) }}</span>
                    </li>
                </ul>
            </section>

            <FeatureBento />

            <AutomationShowcase />

            <section id="steps" class="g-section guest-wrap" aria-labelledby="steps-title">
                <div class="g-head" data-head>
                    <div>
                        <span class="g-eyebrow"><span class="g-dot" />{{ t('welcome.steps.eyebrow') }}</span>
                        <h2 id="steps-title" class="g-h2" data-reveal="lines">{{ t('welcome.steps.title') }}</h2>
                    </div>
                    <p class="g-lead" data-head-lead>{{ t('welcome.steps.description') }}</p>
                </div>

                <ol class="steps">
                    <li
                        v-for="(key, index) in STEPS"
                        :key="key"
                        class="g-card step"
                        data-aos="fade-up"
                        :data-aos-delay="index * 100"
                    >
                        <span class="step-number g-mono">{{ String(index + 1).padStart(2, '0') }}</span>
                        <h3>{{ t(`welcome.steps.items.${key}.title`) }}</h3>
                        <p>{{ t(`welcome.steps.items.${key}.text`) }}</p>
                        <span class="step-path g-mono">{{ t(`welcome.steps.items.${key}.path`) }}</span>
                    </li>
                </ol>
            </section>

            <FaqList />

            <CtaBand />
        </main>

        <GuestFooter />
    </div>
</template>

<style scoped>
/* before / after */
.swap {
    margin: 0;
    padding: 6px 0;
    list-style: none;
}
.swap-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 44px minmax(0, 1fr);
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
}
.swap-row + .swap-row { border-top: 1px solid var(--g-line); }
.was {
    color: var(--g-faint);
    text-decoration: line-through;
    text-decoration-color: color-mix(in srgb, var(--g-st-overdue) 70%, transparent);
    text-decoration-thickness: 1.5px;
}
.arrow {
    width: 44px;
    height: 28px;
    border-radius: 999px;
    display: grid;
    place-items: center;
    background: var(--g-tray);
    color: var(--g-muted);
    font-size: 12px;
    transition: background-color 0.2s ease, color 0.2s ease;
}
.swap-row:hover .arrow {
    background: var(--g-accent-soft);
    color: var(--g-accent);
}
.now {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}
.now i {
    font-size: 12px;
    color: var(--g-accent);
}

/* steps */
.steps {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin: 0;
    padding: 0;
    list-style: none;
}
.step {
    display: grid;
    align-content: start;
    gap: 14px;
    padding: 24px;
}
.step-number {
    font: 500 13px/1 var(--g-font-data);
    color: var(--g-faint);
}
.step h3 {
    font-size: 1.15rem;
    font-weight: 600;
    letter-spacing: -0.025em;
}
.step p {
    font-size: 14.5px;
    color: var(--g-muted);
}
.step-path {
    font-size: 12px;
    color: var(--g-accent);
}

@media (max-width: 980px) {
    .steps { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 640px) {
    .swap-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .arrow { display: none; }
}
@media (max-width: 560px) {
    .steps { grid-template-columns: 1fr; }
}
</style>
