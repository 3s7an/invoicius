<script setup>
import { router, Link } from '@inertiajs/vue3';
import { formatDate } from '@/utils/formatters';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import { useAutomatizationActions } from '@/Pages/Automatizations/Composables/automatizationActions';

const { toggleActive, confirmDelete } = useAutomatizationActions();

defineProps({
    automatizations: {
        type: Array,
        default: () => [],
    },
});

</script>

<template>
    <DataTable :value="automatizations" tableStyle="min-width: 40rem">
        <Column field="type_label" header="Typ">
            <template #body="{ data }">{{ data.type_label }}</template>
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
        <Column header="Akcie">
            <template #body="{ data }">
                <Button
                    :icon="data.is_active ? 'pi pi-pause' : 'pi pi-play'"
                    class="p-button-text p-button-sm"
                    :title="data.is_active ? 'Deaktivovať' : 'Aktivovať'"
                    @click="toggleActive(data)"
                />
                <Link :href="route('automatizations.edit', data.id)">
                    <Button
                        icon="pi pi-pencil"
                        class="p-button-text p-button-sm"
                        title="Upraviť"
                    />
                </Link>
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
