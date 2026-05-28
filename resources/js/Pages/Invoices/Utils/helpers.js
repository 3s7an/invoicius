import { FALLBACK_COUNTRY_LIST } from '@/utils/countries';

export function prefixedId(prefix, name) {
    if (!prefix) return name;
    return `${prefix}-${name}`;
}

export const toYMD = (d = new Date()) =>
    `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
export const todayYMD = () => toYMD();
export const defaultDueYMD = (days = 14) => toYMD(new Date(Date.now() + days * 864e5));
export const formatDate = (v) => (v ? String(v).slice(0, 10) : '');
export const nullIfBlank = (value) => (value === '' || value == null ? null : value);


export function getCountriesSk() {
    try {
        if (typeof Intl.supportedValuesOf !== 'function') return FALLBACK_COUNTRY_LIST;
        const codes = Intl.supportedValuesOf('region').filter((c) => c.length === 2 && c !== 'FX');
        const displayNames = new Intl.DisplayNames(['sk'], { type: 'region' });
        return codes
            .map((code) => ({ code, name: displayNames.of(code) || code }))
            .sort((a, b) => a.name.localeCompare(b.name));
    } catch {
        return FALLBACK_COUNTRY_LIST;
    }
}

