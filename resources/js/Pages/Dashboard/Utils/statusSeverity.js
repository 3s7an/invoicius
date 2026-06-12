export function invoiceStatusSeverity(statusName) {
    const name = String(statusName ?? '').toLowerCase();

    if (name.includes('uhrad') || name.includes('paid')) {
        return 'success';
    }

    if (name.includes('splat') || name.includes('overdue')) {
        return 'danger';
    }

    if (name.includes('koncept') || name.includes('draft')) {
        return 'secondary';
    }

    if (name.includes('odosl') || name.includes('sent')) {
        return 'info';
    }

    return 'warn';
}
