export function automatizationTypeLabel(type) {
    if (type === 'invoice_auto_gen') return 'Automatické generovanie faktúr';
    if (type === 'invoice_report') return 'Mesačný report faktúr';
    if (type === 'invoice_due_reminder') return 'Upozornenie na splatnosť';
    return type;
}

export function defaultAutomatizationForm(automatization = null) {
    const today = new Date().toISOString().slice(0, 10);
    const type = automatization?.type ?? 'invoice_auto_gen';

    return {
        name: automatization?.name ?? automatizationTypeLabel(type),
        recipient_id: automatization?.recipient_id ?? '',
        type,
        date_trigger: automatization?.date_trigger
            ? String(automatization.date_trigger).substring(0, 10)
            : today,
        due_offset_days: automatization?.due_offset_days ?? '',
        is_active: automatization?.is_active ?? true,
        item_names: Array.isArray(automatization?.item_names) && automatization.item_names.length
            ? [...automatization.item_names]
            : [''],
        item_count: Array.isArray(automatization?.item_names) && automatization.item_names.length
            ? automatization.item_names.length
            : 1,
    };
}
