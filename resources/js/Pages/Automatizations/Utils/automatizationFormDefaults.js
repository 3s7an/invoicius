import { AutomatizationType } from '@/Pages/Automatizations/Utils/automatizationTypes';
import { todayYMD } from '@/Pages/Invoices/Utils/helpers';
import { toDateString } from '@/utils/formatters';

export function automatizationTypeLabel(type, automatizationTypes = []) {
    const found = automatizationTypes.find((entry) => entry.value === type);

    if (found) {
        return found.label;
    }

    return type;
}

function defaultItemNames(automatization, type) {
    if (type !== AutomatizationType.InvoiceAutoGen) {
        return [];
    }

    if (Array.isArray(automatization?.item_names) && automatization.item_names.length) {
        return [...automatization.item_names];
    }

    return [''];
}

export function defaultAutomatizationForm(automatization = null) {
    const type = automatization?.type ?? AutomatizationType.InvoiceAutoGen;
    const itemNames = defaultItemNames(automatization, type);

    return {
        recipient_id: automatization?.recipient_id ?? '',
        type,
        date_trigger: automatization?.date_trigger
            ? toDateString(automatization.date_trigger)
            : todayYMD(),
        due_offset_days: automatization?.due_offset_days ?? '',
        is_active: automatization?.is_active ?? true,
        item_names: itemNames,
        item_count: itemNames.length || 1,
    };
}
