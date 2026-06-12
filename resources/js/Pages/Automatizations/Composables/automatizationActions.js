import { router } from '@inertiajs/vue3';
import { AutomatizationType } from '@/Pages/Automatizations/Utils/automatizationTypes';
import { toDateString } from '@/utils/formatters';

export function useAutomatizationActions() {
    function buildUpdatePayload(automatization, overrides = {}) {
        const payload = {
            type: automatization.type,
            date_trigger: toDateString(automatization.date_trigger),
            is_active: automatization.is_active,
            ...overrides,
        };

        if (automatization.type === AutomatizationType.InvoiceAutoGen) {
            payload.recipient_id = automatization.recipient_id;
            payload.item_names = automatization.item_names;
        }

        if (automatization.type === AutomatizationType.InvoiceDueReminder) {
            payload.due_offset_days = automatization.due_offset_days;
        }

        return payload;
    }

    function toggleActive(automatization) {
        router.patch(
            route('automatizations.update', automatization.id),
            buildUpdatePayload(automatization, { is_active: !automatization.is_active }),
            { preserveScroll: true },
        );
    }

    function confirmDelete(automatization) {
        if (!confirm(`Zmazať automatizáciu „${automatization.type_label}“?`)) return;

        router.delete(route('automatizations.destroy', automatization.id), {
            preserveScroll: true,
        });
    }

    return { toggleActive, confirmDelete };
}