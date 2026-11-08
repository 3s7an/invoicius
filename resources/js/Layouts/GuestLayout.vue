<script setup lang="ts">
import '../../css/guest.css';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useGuestMotion } from '@/composables/useGuestMotion';
import GuestNav from '@/Components/Guest/GuestNav.vue';

const { t } = useI18n();

defineProps<{
    eyebrow: string
    title: string
    lead?: string
}>();

const root = ref<HTMLElement | null>(null);

useGuestMotion(root);
</script>

<template>
    <div ref="root" class="guest">
        <GuestNav />

        <main class="auth guest-wrap">
            <div class="g-tray auth-grid">
                <section class="g-card auth-form" data-aos="fade-up">
                    <header>
                        <span class="g-eyebrow" data-intro><span class="g-dot" />{{ eyebrow }}</span>
                        <h1 data-reveal="lines" data-reveal-instant>{{ title }}</h1>
                        <p v-if="lead" class="lead" data-intro>{{ lead }}</p>
                    </header>

                    <slot />

                    <p v-if="$slots.footer" class="switch">
                        <slot name="footer" />
                    </p>
                </section>

                <aside class="auth-side" data-aos="fade-left" data-aos-delay="150">
                    <span class="g-eyebrow side-eyebrow"><span class="g-dot" />invoicius.online</span>
                    <blockquote>{{ t('auth.side.quote') }}</blockquote>
                    <div class="side-stats">
                        <div>
                            <b>{{ t('auth.side.invoiceValue') }}</b>
                            <span>{{ t('auth.side.invoiceLabel') }}</span>
                        </div>
                        <div>
                            <b>{{ t('auth.side.automationValue') }}</b>
                            <span>{{ t('auth.side.automationLabel') }}</span>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</template>

<style scoped>
.auth {
    padding-block: 24px 64px;
}
.auth-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 12px;
    min-height: 640px;
}
.auth-form {
    padding: clamp(28px, 5vw, 56px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 28px;
}
.auth-form h1 {
    margin-top: 14px;
    font-size: clamp(2rem, 4vw, 2.9rem);
    font-weight: 600;
    line-height: 1;
    letter-spacing: -0.045em;
}
.lead {
    margin-top: 12px;
    color: var(--g-muted);
    max-width: 44ch;
}
.switch {
    font-size: 14.5px;
    color: var(--g-muted);
}
.switch :deep(a) {
    color: var(--g-ink);
    font-weight: 500;
    text-decoration: underline;
}
.auth-side {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 28px;
    padding: clamp(24px, 4vw, 44px);
    border-radius: var(--g-r-card);
    background: var(--g-accent-soft);
    border: 1px solid var(--g-accent-line);
    overflow: hidden;
}
.side-eyebrow {
    color: var(--g-muted);
}
.auth-side blockquote {
    margin: 0;
    font: 500 clamp(1.4rem, 2.6vw, 2rem)/1.15 var(--g-font);
    letter-spacing: -0.035em;
    color: var(--g-ink);
    max-width: 20ch;
}
.side-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}
.side-stats div {
    display: grid;
    gap: 4px;
    padding: 16px;
    border-radius: 14px;
    border: 1px solid var(--g-accent-line);
    background: var(--g-surface);
}
.side-stats b {
    font: 600 1.6rem/1 var(--g-font);
    letter-spacing: -0.04em;
}
.side-stats span {
    color: var(--g-muted);
    font-size: 13px;
}
@media (max-width: 860px) {
    .auth-grid {
        grid-template-columns: 1fr;
        min-height: 0;
    }
    .auth-side {
        display: none;
    }
}
</style>
