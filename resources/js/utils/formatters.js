/**
 * Format a numeric value as amount (2 decimal places).
 */
export function formatAmount(value) {
    const num = Number(value ?? 0);
    return Number.isNaN(num) ? '0.00' : num.toFixed(2);
}

/**
 * Normalize a date value to Y-m-d in local calendar (avoids UTC ISO off-by-one).
 */
export function toDateString(value) {
    if (value == null || value === '') {
        return '';
    }

    if (value instanceof Date) {
        if (Number.isNaN(value.getTime())) {
            return '';
        }

        const year = value.getFullYear();
        const month = String(value.getMonth() + 1).padStart(2, '0');
        const day = String(value.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    const str = String(value).trim();

    if (/^\d{4}-\d{2}-\d{2}$/.test(str)) {
        return str;
    }

    const date = new Date(str);

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

/**
 * Format a date value for display (sk-SK: dd.mm.yyyy).
 */
export function formatDate(value) {
    const ymd = toDateString(value);

    if (!ymd) {
        return '—';
    }

    return ymd.split('-').reverse().join('.');
}
