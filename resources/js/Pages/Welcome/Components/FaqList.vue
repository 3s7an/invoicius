<script setup lang="ts">
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const QUESTIONS = ['vat', 'payment', 'look', 'overdue', 'languages'] as const;
</script>

<template>
    <section id="faq" class="g-section guest-wrap" aria-labelledby="faq-title">
        <div class="faq-grid">
            <div data-head>
                <span class="g-eyebrow"><span class="g-dot" />{{ t('welcome.faq.eyebrow') }}</span>
                <h2 id="faq-title" class="g-h2" data-reveal="lines">{{ t('welcome.faq.title') }}</h2>
                <p class="faq-intro" data-head-lead>{{ t('welcome.faq.description') }}</p>
            </div>

            <div class="faq">
                <details
                    v-for="(key, index) in QUESTIONS"
                    :key="key"
                    :open="index === 0"
                    data-aos="fade-up"
                    :data-aos-delay="index * 100"
                >
                    <summary>
                        {{ t(`welcome.faq.items.${key}.q`) }}
                        <span class="toggle"><i class="pi pi-plus" /></span>
                    </summary>
                    <p>{{ t(`welcome.faq.items.${key}.a`) }}</p>
                </details>
            </div>
        </div>
    </section>
</template>

<style scoped>
.faq-grid {
    display: grid;
    grid-template-columns: minmax(0, 4fr) minmax(0, 8fr);
    gap: 32px;
}
.faq-intro {
    margin-top: 18px;
    max-width: 32ch;
    color: var(--g-muted);
}
.faq {
    display: grid;
    gap: 8px;
}
.faq details {
    border: 1px solid var(--g-line);
    border-radius: var(--g-r-card);
    background: var(--g-surface);
}
.faq summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 20px 22px;
    list-style: none;
    font-weight: 500;
    font-size: 16.5px;
    letter-spacing: -0.01em;
    cursor: pointer;
}
.faq summary::-webkit-details-marker { display: none; }
.toggle {
    flex-shrink: 0;
    width: 30px;
    height: 30px;
    border-radius: 999px;
    display: grid;
    place-items: center;
    background: var(--g-tray);
    font-size: 11px;
    transition: transform 0.25s ease, background-color 0.2s ease;
}
.faq details[open] .toggle {
    transform: rotate(45deg);
    background: var(--g-accent-soft);
    color: var(--g-accent);
}
.faq details p {
    max-width: 62ch;
    padding: 0 22px 22px;
    color: var(--g-muted);
}
@media (max-width: 820px) {
    .faq-grid { grid-template-columns: 1fr; }
}
</style>
