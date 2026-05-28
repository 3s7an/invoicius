<script setup>
import { router, Link } from '@inertiajs/vue3';
import { formatDate } from '@/utils/formatters';
import Button from 'primevue/button';

defineProps({
    automatizations: {
        type: Array,
        default: () => [],
    },
});

function recipientName(automatization) {
    const r = automatization.recipient;
    if (!r) return null;
    return r.company_name || r.name || '—';
}

function toggleActive(automatization) {
    router.patch(
        route('automatizations.update', automatization.id),
        { is_active: !automatization.is_active },
        { preserveScroll: true },
    );
}

function typeLabel(type) {
    if (type === 'invoice_auto_gen') return 'Automatické generovanie faktúr';
    if (type === 'invoice_report') return 'Mesačný report faktúr';
    if (type === 'invoice_due_reminder') return 'Upozornenie na splatnosť';
    return type;
}

function confirmDelete(automatization) {
    const label = recipientName(automatization) ?? typeLabel(automatization.type);
    if (!confirm(`Zmazať automatizáciu „${label}“?`)) return;
    router.delete(route('automatizations.destroy', automatization.id), { preserveScroll: true });
}
</script>

<template>
    <div class="space-y-3">
        <article
            v-for="automatization in automatizations"
            :key="automatization.id"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
        >
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-base font-semibold text-gray-900">{{ typeLabel(automatization.type) }}</p>
                    <p v-if="recipientName(automatization)" class="mt-0.5 truncate text-sm text-gray-600">
                        Klient: {{ recipientName(automatization) }}
                    </p>
                    <p v-else class="mt-0.5 text-sm text-gray-600">Pre všetkých klientov</p>
                </div>
                <span
                    class="inline-flex shrink-0 items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                    :class="
                        automatization.is_active
                            ? 'bg-green-100 text-green-700'
                            : 'bg-gray-100 text-gray-500'
                    "
                >
                    {{ automatization.is_active ? 'Aktívne' : 'Neaktívne' }}
                </span>
            </div>

            <dl class="mt-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Nasledujúce spustenie</dt>
                    <dd class="font-medium text-gray-900">{{ formatDate(automatization.date_trigger) }}</dd>
                </div>
            </dl>

            <div class="mt-4 flex items-center justify-end gap-1 border-t border-gray-100 pt-3">
                <Button
                    :icon="automatization.is_active ? 'pi pi-pause' : 'pi pi-play'"
                    class="p-button-text p-button-sm"
                    :title="automatization.is_active ? 'Deaktivovať' : 'Aktivovať'"
                    @click="toggleActive(automatization)"
                />
                <Link :href="route('automatizations.edit', automatization.id)">
                    <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Upraviť" />
                </Link>
                <Button
                    icon="pi pi-trash"
                    class="p-button-text p-button-sm p-button-danger"
                    title="Zmazať"
                    @click="confirmDelete(automatization)"
                />
            </div>
        </article>
    </div>
</template>
