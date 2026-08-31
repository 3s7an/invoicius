import '../css/app.css';
import 'primeicons/primeicons.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import { definePreset } from '@primeuix/themes';
import { sk } from 'primelocale/js/sk.js';
import { en } from 'primelocale/js/en.js';
import { i18n } from './i18n';
import * as Sentry from '@sentry/vue';

const PRIME_LOCALES = { sk, en };

const InvoiciusPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50:  '{emerald.50}',
            100: '{emerald.100}',
            200: '{emerald.200}',
            300: '{emerald.300}',
            400: '{emerald.400}',
            500: '{emerald.500}',
            600: '{emerald.600}',
            700: '{emerald.700}',
            800: '{emerald.800}',
            900: '{emerald.900}',
            950: '{emerald.950}',
        },
    },
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const SENSITIVE_KEYS = [
    'iban', 'ico', 'dic', 'ic_dph', 'recipient_ico', 'recipient_dic', 'recipient_ic_dph',
    'varsym', 'email', 'recipient_name', 'recipient_street', 'recipient_street_num',
    'recipient_city', 'recipient_state', 'total_price', 'vat_price', 'wo_vat_price',
    'notes', 'password', 'password_confirmation', 'current_password',
];

function scrubSensitiveData(value) {
    if (Array.isArray(value)) {
        return value.map(scrubSensitiveData);
    }
    if (value !== null && typeof value === 'object') {
        return Object.fromEntries(
            Object.entries(value).map(([key, val]) => [
                key,
                SENSITIVE_KEYS.includes(key.toLowerCase()) ? '[Filtered]' : scrubSensitiveData(val),
            ]),
        );
    }
    return value;
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const initialLocale = props.initialPage.props.locale ?? 'sk';
        i18n.global.locale.value = initialLocale;
        document.documentElement.lang = initialLocale;

        const app = createApp({ render: () => h(App, props) });

        if (import.meta.env.VITE_SENTRY_DSN) {
            Sentry.init({
                app,
                dsn: import.meta.env.VITE_SENTRY_DSN,
                environment: import.meta.env.MODE,
                integrations: [Sentry.browserTracingIntegration()],
                tracesSampleRate: 0.2,
                sendDefaultPii: false,
                // Known-benign noise filtered out before it reaches Sentry:
                //  - "Object Not Found Matching Id...": raised by third-party browser
                //    extensions / the legacy Edge-IE COM bridge hooking into page
                //    objects, not by application code.
                //  - "Request aborted": Inertia uses axios internally for page visits
                //    and cancels the in-flight request whenever a new visit starts
                //    before the previous one finishes (fast navigation, closing a modal,
                //    prefetching). Expected SPA behaviour, not an application error.
                //  - "Network Error": generic axios/XHR failure raised client-side when
                //    a request never gets a response (browser offline, DNS hiccup, ad
                //    blocker, or a brief connection drop during a backend deploy/restart).
                //    Carries no request/response detail to act on and does not indicate
                //    an application bug.
                ignoreErrors: [
                    /Object Not Found Matching Id/,
                    'Request aborted',
                    'Network Error',
                ],
                beforeBreadcrumb(breadcrumb) {
                    if (breadcrumb.data) {
                        breadcrumb.data = scrubSensitiveData(breadcrumb.data);
                    }
                    return breadcrumb;
                },
                beforeSend(event) {
                    if (event.request?.data) {
                        event.request.data = scrubSensitiveData(event.request.data);
                    }
                    if (event.extra) {
                        event.extra = scrubSensitiveData(event.extra);
                    }
                    return event;
                },
            });
        }

        return app
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .use(PrimeVue, {
                locale: PRIME_LOCALES[initialLocale] ?? sk,
                theme: {
                    preset: InvoiciusPreset,
                    options: {
                        darkModeSelector: '.never-apply-dark-mode',
                    },
                },
            })
            .mount(el);
    },
    progress: {
        color: '#10b981',
    },
});
