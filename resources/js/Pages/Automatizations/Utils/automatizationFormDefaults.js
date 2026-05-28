export function defaultAutomatizationForm(automatization = null) {
    return {
        recipient_id: automatization?.recipient_id ?? '',
        type: automatization?.type ?? 'invoice_auto_gen',
        date_trigger: automatization?.date_trigger
            ? String(automatization.date_trigger).substring(0, 10)
            : '',
        is_active: automatization?.is_active ?? true,
    };
}

