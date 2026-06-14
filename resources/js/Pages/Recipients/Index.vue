<script setup lang=”ts”>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import RecipientCardList from '@/Pages/Recipients/Components/RecipientCardList.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import type { RecipientResource } from '@/types';

defineProps<{
    recipients: RecipientResource[]
}>();

function displayName(recipient: RecipientResource): string {
    return recipient.company_name || recipient.name || '—';
}

function confirmDeleteRecipient(recipient: RecipientResource): void {
    if (!confirm(`Zmazať „${displayName(recipient)}”?`)) return;
    router.delete(route('recipients.destroy', recipient.id));
}
</script>

<template>
    <Head title="Klienti" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <PageHeader title="Klienti">
                <template #actions>
                    <Link :href="route('recipients.create')">
                        <Button label="Nový klient" icon="pi pi-plus" class="p-button-raised p-button-sm" />
                    </Link>
                </template>
            </PageHeader>
            <RecipientCardList class="lg:hidden" :recipients="recipients" />

            <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:block">
            <DataTable :value="recipients" tableStyle="min-width: 50rem">
                <Column field="company_name" header="Názov / obchodné meno">
                    <template #body="{ data }">{{ displayName(data) }}</template>
                </Column>
                <Column field="city" header="Mesto">
                    <template #body="{ data }">{{ data.city || '—' }}</template>
                </Column>
                <Column field="street" header="Ulica">
                    <template #body="{ data }">{{ data.street || '—' }}</template>
                </Column>
                <Column field="street_num" header="Číslo ulice">
                    <template #body="{ data }">{{ data.street_num || '—' }}</template>
                </Column>
                <Column header="Akcie">
                    <template #body="{ data }">
                        <Link :href="route('recipients.edit', data.id)">
                            <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Upraviť" />
                        </Link>
                        <Button
                            icon="pi pi-trash"
                            class="p-button-text p-button-sm p-button-danger"
                            title="Zmazať"
                            @click="confirmDeleteRecipient(data)"
                        />
                    </template>
                </Column>
                </DataTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
