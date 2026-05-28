/**
 * Format a numeric value as amount (2 decimal places).
 */
export function formatAmount(value) {
    const num = Number(value ?? 0);
    return Number.isNaN(num) ? '0.00' : num.toFixed(2);
}

/**
 * Format a date value for display (sk-SK: dd.mm.yyyy).
 */
export function formatDate(value) {
    if (value == null) return '—';

    if (typeof value === 'string' && /^\d{4}-\d{2}-\d{2}/.test(value)) {
        return value.slice(0, 10).split('-').reverse().join('.');
    }

    const date = value instanceof Date ? value : new Date(value);
    if (Number.isNaN(date.getTime())) return '—';
    return new Intl.DateTimeFormat('sk-SK', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(date);
}
