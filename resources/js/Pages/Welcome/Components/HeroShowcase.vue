<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import dashboardShot from '@/assets/app-screenshot.png';
import invoicesShot from '@/assets/app-screenshot-2.png';
import recipientsShot from '@/assets/app-screenshot-3.png';
import automatizationsShot from '@/assets/app-screenshot-4.png';
import { useDemoFormat } from '../Composables/useDemoFormat';

const { t } = useI18n();
const { money } = useDemoFormat();

const SHOTS = [
    { key: 'dashboard', icon: 'pi-th-large', src: dashboardShot },
    { key: 'invoices', icon: 'pi-file', src: invoicesShot },
    { key: 'recipients', icon: 'pi-users', src: recipientsShot },
    { key: 'automatizations', icon: 'pi-bolt', src: automatizationsShot },
] as const;

const ROTATE_MS = 5000;

const current = ref(0);
const progress = ref(0);
let startedAt = 0;
let paused = false;
let frame = 0;

function select(index: number) {
    current.value = index;
    progress.value = 0;
    startedAt = performance.now();
}

function tick(now: number) {
    if (!paused) {
        progress.value = Math.min(1, (now - startedAt) / ROTATE_MS);
        if (progress.value >= 1) {
            select((current.value + 1) % SHOTS.length);
        }
    }
    frame = requestAnimationFrame(tick);
}

function pause() {
    paused = true;
}

function resume() {
    paused = false;
    startedAt = performance.now() - progress.value * ROTATE_MS;
}

onMounted(() => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    startedAt = performance.now();
    frame = requestAnimationFrame(tick);
});

onUnmounted(() => cancelAnimationFrame(frame));
</script>

<template>
    <section class="hero guest-wrap">
        <span class="g-eyebrow" data-intro><span class="g-dot" />{{ t('welcome.hero.eyebrow') }}</span>

        <h1 class="hero-title" data-reveal="lines" data-reveal-instant>
            {{ t('welcome.hero.titleMain') }}
            <span class="soft">{{ t('welcome.hero.titleSoft') }}</span><span class="stop">.</span>
        </h1>

        <div class="hero-row" data-intro>
            <div>
                <p class="hero-sub">{{ t('welcome.hero.subtitle') }}</p>
                <div class="hero-cta">
                    <Link :href="route('register')" class="g-pill">
                        {{ t('welcome.hero.ctaRegister') }}
                        <span class="g-knob"><i class="pi pi-arrow-up-right" /></span>
                    </Link>
                    <Link :href="route('login')" class="g-ghost">
                        {{ t('welcome.hero.ctaLogin') }}
                    </Link>
                </div>
            </div>
            <div class="hero-meta">
                <span class="g-mono">{{ t('welcome.hero.metaFeatures') }}</span>
                <span class="g-mono">{{ t('welcome.hero.metaSpeed') }}</span>
            </div>
        </div>

        <div class="showcase" data-intro>
            <div class="float float--paid" data-intro-fade>
                <span class="float-icon"><i class="pi pi-check" /></span>
                <div>
                    <b class="g-mono">2026-0042</b> · Aurora s.r.o.
                    <small>{{ t('invoiceStatus.paid') }} · {{ money(1845) }}</small>
                </div>
            </div>
            <div class="float float--reminder" data-intro-fade>
                <span class="float-icon"><i class="pi pi-bell" /></span>
                <div>
                    {{ t('welcome.hero.floatReminder') }}
                    <small>Bistro Lipa · {{ t('welcome.hero.floatReminderDue', { days: 3 }) }}</small>
                </div>
            </div>

            <div class="g-tray frame" @mouseenter="pause" @mouseleave="resume">
                <div class="g-card frame-card">
                    <div class="frame-bar">
                        <span class="addr">
                            <i class="pi pi-lock" />
                            invoicius.online/<b>{{ SHOTS[current].key }}</b>
                        </span>
                        <div class="tabs" role="tablist" :aria-label="t('welcome.hero.tabsLabel')">
                            <button
                                v-for="(shot, index) in SHOTS"
                                :key="shot.key"
                                type="button"
                                role="tab"
                                :aria-selected="index === current"
                                @click="select(index)"
                            >
                                <i class="pi" :class="shot.icon" />
                                {{ t(`welcome.hero.tabs.${shot.key}`) }}
                            </button>
                        </div>
                    </div>
                    <div class="shots">
                        <img
                            v-for="(shot, index) in SHOTS"
                            :key="shot.key"
                            :src="shot.src"
                            :alt="t(`welcome.hero.shotAlt.${shot.key}`)"
                            :class="{ on: index === current }"
                            :loading="index === 0 ? 'eager' : 'lazy'"
                        >
                    </div>
                    <div class="progress" aria-hidden="true">
                        <i :style="{ width: `${progress * 100}%` }" />
                    </div>
                </div>
            </div>
        </div>

        <div class="g-card metrics" data-aos="fade-up">
            <div class="metric">
                <strong><span data-count="2">2</span><em> {{ t('welcome.metrics.minutesUnit') }}</em></strong>
                <span>{{ t('welcome.metrics.minutesLabel') }}</span>
            </div>
            <div class="metric">
                <strong><span data-count="4">4</span><em> {{ t('welcome.metrics.statusesUnit') }}</em></strong>
                <span>{{ t('welcome.metrics.statusesLabel') }}</span>
            </div>
            <div class="metric">
                <strong>SK<em>/EN</em></strong>
                <span>{{ t('welcome.metrics.languagesLabel') }}</span>
            </div>
            <div class="metric">
                <strong><span data-count="24">24</span><em>/7</em></strong>
                <span>{{ t('welcome.metrics.automationLabel') }}</span>
            </div>
        </div>
    </section>
</template>

<style scoped>
.hero {
    padding-block: 56px 0;
}
.hero-title {
    margin-top: 22px;
    max-width: 13ch;
    font-size: clamp(2.6rem, 7.4vw, 6.1rem);
    font-weight: 600;
    line-height: 0.94;
    letter-spacing: -0.055em;
}
.soft { color: var(--g-faint); }
.stop { color: var(--g-accent-dot); }
.hero-row {
    margin-top: 36px;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 32px;
    align-items: end;
}
.hero-sub {
    max-width: 46ch;
    font-size: 1.125rem;
    color: var(--g-muted);
}
.hero-cta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 26px;
}
.hero-meta {
    display: grid;
    gap: 10px;
    text-align: right;
    font-size: 13px;
    color: var(--g-muted);
}

/* product frame */
.showcase {
    position: relative;
    margin-top: 56px;
}
.frame-card {
    overflow: hidden;
}
.frame-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 10px 10px 10px 16px;
    border-bottom: 1px solid var(--g-line);
}
.addr {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
    font: 400 12.5px/1 var(--g-font-data);
    color: var(--g-faint);
}
.addr i { font-size: 10px; }
.addr b {
    color: var(--g-ink);
    font-weight: 500;
}
.tabs {
    display: inline-flex;
    max-width: 100%;
    padding: 3px;
    border-radius: 999px;
    background: var(--g-tray);
    overflow-x: auto;
}
.tabs button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 14px;
    border: 0;
    border-radius: 999px;
    background: transparent;
    color: var(--g-muted);
    font: 500 13.5px/1 var(--g-font);
    white-space: nowrap;
    cursor: pointer;
}
.tabs button i { font-size: 12px; }
.tabs button[aria-selected="true"] {
    background: var(--g-surface);
    color: var(--g-ink);
    box-shadow: 0 1px 2px rgb(17 24 39 / 0.08);
}
.tabs button[aria-selected="true"] i { color: var(--g-accent); }
.shots {
    display: grid;
    background: #ffffff;
}
.shots img {
    grid-area: 1 / 1;
    display: block;
    width: 100%;
    max-width: 100%;
    aspect-ratio: 1848 / 926;
    object-fit: cover;
    object-position: top left;
    opacity: 0;
    transition: opacity 0.5s ease;
}
.shots img.on { opacity: 1; }
.progress {
    height: 2px;
    background: var(--g-line);
}
.progress i {
    display: block;
    height: 100%;
    background: var(--g-accent-dot);
}

.float {
    position: absolute;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    background: var(--g-surface);
    border: 1px solid var(--g-line);
    border-radius: 16px;
    box-shadow: var(--g-shadow);
    font-size: 13.5px;
}
.float small {
    display: block;
    color: var(--g-faint);
    font-size: 12px;
}
.float-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    background: var(--g-accent-soft);
    color: var(--g-accent);
}
.float--paid {
    left: -18px;
    bottom: 56px;
    animation: bob 6s ease-in-out infinite;
}
.float--reminder {
    right: -18px;
    top: 92px;
    animation: bob 7s ease-in-out -2s infinite;
}
@keyframes bob {
    50% { transform: translateY(-8px); }
}

/* metrics strip */
.metrics {
    margin-top: 20px;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
.metric {
    display: grid;
    gap: 6px;
    padding: 22px 24px;
}
.metric + .metric { border-left: 1px solid var(--g-line); }
.metric strong {
    font: 600 clamp(1.6rem, 3vw, 2.1rem)/1 var(--g-font);
    letter-spacing: -0.04em;
    font-variant-numeric: tabular-nums;
}
.metric strong em {
    font-style: normal;
    color: var(--g-accent);
}
.metric span {
    font-size: 13.5px;
    color: var(--g-muted);
}

@media (max-width: 1080px) {
    .float--paid { left: 16px; }
    .float--reminder { right: 16px; }
}
@media (max-width: 760px) {
    .hero-row { grid-template-columns: 1fr; }
    .hero-meta { text-align: left; }
    .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .metric:nth-child(3) { border-left: 0; }
    .metric:nth-child(n + 3) { border-top: 1px solid var(--g-line); }
}
@media (max-width: 720px) {
    .float { display: none; }
}
</style>
