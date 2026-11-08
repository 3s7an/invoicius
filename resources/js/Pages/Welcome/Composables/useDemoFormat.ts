import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

/**
 * Locale-aware formatters for the landing page mock-ups (EUR amounts, dates, months).
 */
export function useDemoFormat() {
    const { locale } = useI18n();

    const intlLocale = computed(() => (locale.value === 'sk' ? 'sk-SK' : 'en-GB'));

    const money = (value: number, fractionDigits = 2): string =>
        new Intl.NumberFormat(intlLocale.value, {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: fractionDigits,
            maximumFractionDigits: fractionDigits,
        }).format(value);

    const amount = (value: number): string =>
        new Intl.NumberFormat(intlLocale.value, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(value);

    const date = (value: Date): string =>
        new Intl.DateTimeFormat(intlLocale.value, { day: 'numeric', month: 'numeric', year: 'numeric' }).format(value);

    const month = (value: Date): string =>
        new Intl.DateTimeFormat(intlLocale.value, { month: 'long' }).format(value);

    return { money, amount, date, month };
}
