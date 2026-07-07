import { FALLBACK_COUNTRY_LIST } from '@/utils/countries';
import { toDateString } from '@/utils/formatters';
import { i18n } from '@/i18n';

export function prefixedId(prefix: string | undefined, name: string): string {
    if (!prefix) return name;
    return `${prefix}-${name}`;
}

export const toYMD = (d = new Date()): string =>
    `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;

export const ymdToDate = (ymd: string | null | undefined): Date | null => {
    if (!ymd || !/^\d{4}-\d{2}-\d{2}/.test(String(ymd))) return null;
    const [y, m, d] = String(ymd).slice(0, 10).split('-').map(Number);
    return new Date(y, m - 1, d);
};

export const todayYMD = (): string => toYMD();
export const defaultDueYMD = (days = 14): string => toYMD(new Date(Date.now() + days * 864e5));
export const formatDate = (v: unknown): string => toDateString(v);

export function nullIfBlank(value: string | null | undefined): string | null;
export function nullIfBlank(value: number | null | undefined): number | null;
export function nullIfBlank(value: string | number | null | undefined): string | number | null {
    return value === '' || value == null ? null : value;
}

export function getCountries(locale: string = 'sk'): { code: string; name: string }[] {
    try {
        if (typeof Intl.supportedValuesOf !== 'function') return FALLBACK_COUNTRY_LIST;
        // 'region' is a valid non-standard key supported by modern engines but absent from TS typings
        const codes = (Intl.supportedValuesOf as (key: string) => string[])('region').filter(
            (c) => c.length === 2 && c !== 'FX'
        );
        const displayNames = new Intl.DisplayNames([locale], { type: 'region' });
        return codes
            .map((code) => ({ code, name: displayNames.of(code) ?? code }))
            .sort((a, b) => a.name.localeCompare(b.name));
    } catch {
        return FALLBACK_COUNTRY_LIST;
    }
}

export function invoiceStatusLabel(code: string | null | undefined, fallback?: string | null): string {
    if (!code) return fallback ?? '—';

    const key = `invoiceStatus.${code}`;
    return i18n.global.te(key) ? i18n.global.t(key) : (fallback ?? code);
}

export function currencyName(symbol: string | null | undefined, fallback?: string | null): string {
    if (!symbol) return fallback ?? '';

    const key = `currency.${symbol}`;
    return i18n.global.te(key) ? i18n.global.t(key) : (fallback ?? symbol);
}
