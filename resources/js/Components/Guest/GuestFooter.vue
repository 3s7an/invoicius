<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import GuestBrand from '@/Components/Guest/GuestBrand.vue';

const { t } = useI18n();

const SECTIONS = ['features', 'automations', 'faq'] as const;
const year = new Date().getFullYear();
</script>

<template>
    <footer class="footer" data-stagger>
        <div class="guest-wrap">
            <div class="foot">
                <div data-stagger-item>
                    <GuestBrand />
                    <p>{{ t('welcome.footer.tagline') }}</p>
                </div>
                <div data-stagger-item>
                    <h4>{{ t('welcome.footer.product') }}</h4>
                    <ul>
                        <li v-for="id in SECTIONS" :key="id">
                            <a :href="`${route('home')}#${id}`">{{ t(`welcome.nav.${id}`) }}</a>
                        </li>
                    </ul>
                </div>
                <div data-stagger-item>
                    <h4>{{ t('welcome.footer.account') }}</h4>
                    <ul>
                        <li><Link :href="route('login')">{{ t('welcome.nav.login') }}</Link></li>
                        <li><Link :href="route('register')">{{ t('welcome.footer.register') }}</Link></li>
                        <li><Link :href="route('password.request')">{{ t('welcome.footer.forgotPassword') }}</Link></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="wordmark" aria-hidden="true" data-stagger-item>invoicius</div>

        <div class="guest-wrap">
            <div class="legal" data-stagger-item>
                <span>Invoicius · {{ year }}</span>
                <span>invoicius.online</span>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.footer {
    padding-block: 72px 0;
    overflow: hidden;
}
.foot {
    display: grid;
    grid-template-columns: minmax(0, 2fr) repeat(2, minmax(0, 1fr));
    gap: 32px;
}
.foot p {
    color: var(--g-muted);
    max-width: 36ch;
    margin-top: 14px;
    font-size: 15px;
}
.foot h4 {
    margin: 0 0 14px;
    font: 500 12px/1 var(--g-font-data);
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--g-faint);
}
.foot ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 10px;
}
.foot ul a {
    text-decoration: none;
    color: var(--g-ink);
    font-size: 15px;
}
.foot ul a:hover {
    color: var(--g-accent);
}
.wordmark {
    margin-top: 56px;
    font: 700 clamp(4.5rem, 21vw, 18.5rem)/0.78 var(--g-font);
    letter-spacing: -0.075em;
    color: var(--g-tray);
    white-space: nowrap;
    user-select: none;
    text-align: center;
    transform: translateY(6%);
}
.legal {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    padding-block: 18px 22px;
    border-top: 1px solid var(--g-line);
    font: 400 12.5px/1.4 var(--g-font-data);
    color: var(--g-faint);
}
@media (max-width: 720px) {
    .foot { grid-template-columns: 1fr 1fr; }
    .foot > div:first-child { grid-column: 1 / -1; }
}
</style>
