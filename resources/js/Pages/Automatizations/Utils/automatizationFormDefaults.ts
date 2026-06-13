import { AutomatizationType } from '@/Pages/Automatizations/Utils/automatizationTypes';
import { todayYMD } from '@/Pages/Invoices/Utils/helpers';
import { toDateString } from '@/utils/formatters';
import type { Automatization, AutomatizationTypeOption } from './types';

export interface AutomatizationFormData {
    recipient_id: number | string
    type: string
    date_trigger: string
    due_offset_days: number | string
    is_active: boolean
    item_names: string[]
    item_count: number
}

export function automatizationTypeLabel(type: string, automatizationTypes: AutomatizationTypeOption[] = []): string {
    const found = automatizationTypes.find((entry) => entry.value === type);
    return found ? found.label : type;
}

function defaultItemNames(automatization: Automatization | null, type: string): string[] {
    if (type !== AutomatizationType.InvoiceAutoGen) return [];
    if (Array.isArray(automatization?.item_names) && automatization.item_names.length) {
        return [...automatization.item_names];
    }
    return [''];
}

export function defaultAutomatizationForm(automatization: Automatization | null = null): AutomatizationFormData {
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
