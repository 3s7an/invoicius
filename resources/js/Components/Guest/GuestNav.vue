<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import GuestBrand from '@/Components/Guest/GuestBrand.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const { t } = useI18n();

defineProps<{
    sectionLinks?: boolean
}>();

const SECTIONS = ['features', 'automations', 'steps', 'faq'] as const;
</script>

<template>
    <div class="nav-shell">
        <div class="guest-wrap">
            <nav class="nav" :aria-label="t('welcome.nav.label')">
                <GuestBrand />

                <div v-if="sectionLinks" class="nav-links">
                    <a v-for="id in SECTIONS" :key="id" :href="`#${id}`">
                        {{ t(`welcome.nav.${id}`) }}
                    </a>
                </div>

                <div class="nav-right">
                    <LanguageSwitcher variant="segmented" class="nav-lang" />
                    <Link :href="route('login')" class="nav-login">
                        {{ t('welcome.nav.login') }}
                    </Link>
                    <Link :href="route('register')" class="g-pill g-pill--sm">
                        {{ t('welcome.nav.register') }}
                        <span class="g-knob"><i class="pi pi-arrow-up-right" /></span>
                    </Link>
                </div>
            </nav>
        </div>
    </div>
</template>

<style scoped>
.nav-shell {
    position: sticky;
    top: 0;
    z-index: 50;
    padding-block: 14px;
}
.nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    height: 60px;
    padding: 0 8px 0 18px;
    border-radius: 999px;
    background: rgb(255 255 255 / 0.82);
    border: 1px solid var(--g-line);
    backdrop-filter: blur(16px) saturate(160%);
    -webkit-backdrop-filter: blur(16px) saturate(160%);
}
.nav-links {
    display: flex;
    gap: 4px;
}
.nav-links a {
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 14.5px;
    color: var(--g-muted);
    text-decoration: none;
    transition: background-color 0.2s ease, color 0.2s ease;
}
.nav-links a:hover {
    background: var(--g-tray);
    color: var(--g-ink);
}
.nav-right {
    display: flex;
    align-items: center;
    gap: 8px;
}
.nav-login {
    font-size: 14.5px;
    text-decoration: none;
    padding: 8px 12px;
    color: var(--g-ink);
}
@media (max-width: 920px) {
    .nav-links { display: none; }
}
@media (max-width: 560px) {
    .nav-login,
    .nav-lang { display: none; }
    .nav { padding-left: 14px; }
}
</style>
