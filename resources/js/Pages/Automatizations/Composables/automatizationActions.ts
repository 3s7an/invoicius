import { router } from '@inertiajs/vue3';
import type { FormDataConvertible } from '@inertiajs/core';
import { i18n } from '@/i18n';
import { AutomatizationType } from '@/Pages/Automatizations/Utils/automatizationTypes';
import { toDateString } from '@/utils/formatters';
import type { Automatization } from '../Utils/types';

type Payload = Record<string, FormDataConvertible>;

export function useAutomatizationActions() {
    function buildUpdatePayload(automatization: Automatization, overrides: Payload = {}): Payload {
        const payload: Payload = {
            type: automatization.type,
            date_trigger: automatization.date_trigger ? toDateString(automatization.date_trigger) : null,
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

    function toggleActive(automatization: Automatization): void {
        router.patch(
            route('automatizations.update', automatization.id),
            buildUpdatePayload(automatization, { is_active: !automatization.is_active }),
            { preserveScroll: true },
        );
    }

    function confirmDelete(automatization: Automatization): void {
        if (!confirm(i18n.global.t('automatizations.deleteConfirm', { label: automatization.type_label }))) return;

        router.delete(route('automatizations.destroy', automatization.id), {
            preserveScroll: true,
        });
    }

    return { toggleActive, confirmDelete };
}
