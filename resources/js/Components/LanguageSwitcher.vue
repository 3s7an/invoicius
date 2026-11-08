<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { usePrimeVue } from 'primevue/config';
import { sk } from 'primelocale/js/sk.js';
import { en } from 'primelocale/js/en.js';
import type { AppLocale } from '@/types/inertia';

const PRIME_LOCALES = { sk, en };

withDefaults(defineProps<{
    variant?: 'flags' | 'segmented'
}>(), {
    variant: 'flags',
});

const SEGMENTS: { value: AppLocale; label: string; name: string }[] = [
    { value: 'sk', label: 'SK', name: 'Slovenčina' },
    { value: 'en', label: 'EN', name: 'English' },
];

const { locale } = useI18n();
const primevue = usePrimeVue();
const page = usePage();

function setLocale(lang: AppLocale) {
    if (lang === locale.value) return;

    router.post(route('locale.update', lang), {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            locale.value = lang;
            primevue.config.locale = PRIME_LOCALES[lang];
            document.documentElement.lang = lang;
        },
    });
}
</script>

<template>
    <div v-if="variant === 'segmented'" class="lang-seg" role="group" aria-label="Language">
        <button
            v-for="segment in SEGMENTS"
            :key="segment.value"
            type="button"
            :aria-pressed="page.props.locale === segment.value"
            :aria-label="segment.name"
            :title="segment.name"
            @click="setLocale(segment.value)"
        >
            {{ segment.label }}
        </button>
    </div>
    <div v-else class="flex items-center gap-1 text-xs font-semibold text-gray-500">
        <button
            type="button"
            class="rounded px-1.5 py-1 text-base leading-none transition-colors duration-150"
            :class="page.props.locale === 'sk' ? 'bg-emerald-50 ring-1 ring-emerald-200' : 'opacity-50 hover:opacity-100'"
            aria-label="Slovenčina"
            title="Slovenčina"
            @click="setLocale('sk')"
        >
            🇸🇰
        </button>
        <span class="text-gray-300">/</span>
        <button
            type="button"
            class="rounded px-1.5 py-1 text-base leading-none transition-colors duration-150"
            :class="page.props.locale === 'en' ? 'bg-emerald-50 ring-1 ring-emerald-200' : 'opacity-50 hover:opacity-100'"
            aria-label="English"
            title="English"
            @click="setLocale('en')"
        >
            🇬🇧
        </button>
    </div>
</template>

<style scoped>
.lang-seg {
    display: inline-flex;
    padding: 3px;
    border-radius: 999px;
    background: var(--g-tray, #e7ebe9);
}
.lang-seg button {
    border: 0;
    background: transparent;
    color: var(--g-muted, #4b5563);
    cursor: pointer;
    font: 500 12px/1 var(--g-font-data, var(--font-mono));
    padding: 7px 10px;
    border-radius: 999px;
    transition: background-color 0.15s ease, color 0.15s ease;
}
.lang-seg button[aria-pressed="true"] {
    background: var(--g-surface, #ffffff);
    color: var(--g-ink, #111827);
    box-shadow: 0 1px 2px rgb(17 24 39 / 0.08);
}
</style>
