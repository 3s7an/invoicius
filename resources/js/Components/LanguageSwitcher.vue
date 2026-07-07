<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { usePrimeVue } from 'primevue/config';
import { sk } from 'primelocale/js/sk.js';
import { en } from 'primelocale/js/en.js';
import type { AppLocale } from '@/types/inertia';

const PRIME_LOCALES = { sk, en };

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
    <div class="flex items-center gap-1 text-xs font-semibold text-gray-500">
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
