export function defaultAutomatizationForm(automatization = null) {
    const today = new Date().toISOString().slice(0, 10);

    return {
        recipient_id: automatization?.recipient_id ?? '',
        type: automatization?.type ?? 'invoice_auto_gen',
        date_trigger: automatization?.date_trigger
            ? String(automatization.date_trigger).substring(0, 10)
            : today,
        due_offset_days: automatization?.due_offset_days ?? '',
        is_active: automatization?.is_active ?? true,
    };
}

