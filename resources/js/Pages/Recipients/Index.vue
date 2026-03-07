<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

const props = defineProps({
    recipients: {
        type: Array,
        default: () => [],
    },
});

function displayName(recipient) {
    return recipient.company_name || recipient.name || '—';
}

function confirmDeleteRecipient(recipient) {
    if (!confirm(`Delete "${displayName(recipient)}"?`)) return;
    router.delete(route('recipients.destroy', recipient.id));
}
</script>

<template>
    <Head title="Recipients" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-lg font-medium text-gray-900">Recipients</h1>
                <Link :href="route('recipients.create')">
                    <Button label="New recipient" icon="pi pi-plus" class="p-button-raised p-button-sm" />
                </Link>
            </div>
                <DataTable :value="recipients" tableStyle="min-width: 50rem">
                    <Column field="company_name" header="Name / Company name">
                        <template #body="{ data }">{{ displayName(data) }}</template>
                    </Column>
                    <Column field="city" header="City">
                        <template #body="{ data }">{{ data.city || '—' }}</template>
                    </Column>
                    <Column field="street" header="Street">
                        <template #body="{ data }">{{ data.street || '—' }}</template>
                    </Column>
                    <Column field="street_num" header="Street number">
                        <template #body="{ data }">{{ data.street_num || '—' }}</template>
                    </Column>
                    <Column header="Actions">
                        <template #body="{ data }">
                            <Link :href="route('recipients.edit', data.id)">
                                <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Edit" />
                            </Link>
                            <Button
                                icon="pi pi-trash"
                                class="p-button-text p-button-sm p-button-danger"
                                title="Delete"
                                @click="confirmDeleteRecipient(data)"
                            />
                        </template>
                    </Column>
                </DataTable>
        </div>
    </AuthenticatedLayout>
</template>
