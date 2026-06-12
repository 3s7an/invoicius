import { router } from '@inertiajs/vue3';
import { toDateString } from '@/utils/formatters';

export function useAutomatizationActions() {
    function buildUpdatePayload(automatization, overrides = {}) {
        return {
            recipient_id: automatization.recipient_id,
            type: automatization.type,
            date_trigger: toDateString(automatization.date_trigger),
            due_offset_days: automatization.due_offset_days,
            item_names: automatization.item_names,
            is_active: automatization.is_active,
            ...overrides,
        };
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