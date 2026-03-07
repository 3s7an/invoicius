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
    const date = value instanceof Date ? value : new Date(value);
    if (Number.isNaN(date.getTime())) return '—';
    return new Intl.DateTimeFormat('sk-SK', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(date);
}
