import { AutomatizationType } from '@/Pages/Automatizations/Utils/automatizationTypes';

export function automatizationTypeLabel(type, automatizationTypes = []) {
    const found = automatizationTypes.find((entry) => entry.value === type);

    if (found) {
        return found.label;
    }

    return type;
}

export function defaultAutomatizationForm(automatization = null, automatizationTypes = []) {
    const today = new Date().toISOString().slice(0, 10);
    const type = automatization?.type ?? AutomatizationType.InvoiceAutoGen;

    return {
        name: automatization?.name ?? automatizationTypeLabel(type, automatizationTypes),
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
