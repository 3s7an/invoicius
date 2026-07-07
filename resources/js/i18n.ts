import { createI18n } from 'vue-i18n';
import en from './lang/en';
import sk from './lang/sk';

export const i18n = createI18n({
    legacy: false,
    locale: 'sk',
    fallbackLocale: 'sk',
    messages: { en, sk },
});
