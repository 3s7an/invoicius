<script setup>
import { router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

defineProps({
    automatizations: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['edit']);

function recipientName(automatization) {
    const r = automatization.recipient;
    if (!r) return '—';
    return r.company_name || r.name || '—';
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('sk-SK');
}

function toggleActive(automatization) {
    router.patch(route('automatizations.update', automatization.id), {
        is_active: !automatization.is_active,
    }, { preserveScroll: true });
}

function typeLabel(type) {
    if (type === 'invoice_auto_gen') return 'Automatické generovanie faktúr';
    return type;
}

function confirmDelete(automatization) {
    if (!confirm(`Zmazať automatizáciu pre „${recipientName(automatization)}“?`)) return;
    router.delete(route('automatizations.destroy', automatization.id), { preserveScroll: true });
}
</script>

<template>
    <DataTable :value="automatizations" tableStyle="min-width: 40rem">
        <Column header="Odberateľ">
            <template #body="{ data }">{{ recipientName(data) }}</template>
        </Column>
        <Column field="type" header="Typ">
            <template #body="{ data }">
                <span class="rounded bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">
                    {{ typeLabel(data.type) }}
                </span>
            </template>
        </Column>
        <Column header="Nasledujúce spustenie">
            <template #body="{ data }">{{ formatDate(data.date_trigger) }}</template>
        </Column>
        <Column header="Stav">
            <template #body="{ data }">
                <span
                    class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                    :class="data.is_active
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-500'"
                >
                    {{ data.is_active ? 'Aktívne' : 'Neaktívne' }}
                </span>
            </template>
        </Column>
        <Column header="Posledné spustenie">
            <template #body="{ data }">{{ formatDate(data.last_run_at) }}</template>
        </Column>
        <Column header="Akcie">
            <template #body="{ data }">
                <Button
                    :icon="data.is_active ? 'pi pi-pause' : 'pi pi-play'"
                    class="p-button-text p-button-sm"
                    :title="data.is_active ? 'Deaktivovať' : 'Aktivovať'"
                    @click="toggleActive(data)"
                />
                <Button
                    icon="pi pi-pencil"
                    class="p-button-text p-button-sm"
                    title="Upraviť"
                    @click="emit('edit', data)"
                />
                <Button
                    icon="pi pi-trash"
                    class="p-button-text p-button-sm p-button-danger"
                    title="Zmazať"
                    @click="confirmDelete(data)"
                />
            </template>
        </Column>
    </DataTable>
</template>
