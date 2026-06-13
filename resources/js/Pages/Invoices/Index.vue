<template>
    <Head title="Faktúry" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <PageHeader title="Faktúry">
                <template #actions>
                    <Link :href="route('invoices.create')">
                        <Button label="Nová faktúra" icon="pi pi-plus" class="p-button-raised p-button-sm" />
                    </Link>
                </template>
            </PageHeader>
            <InvoiceCardList
                class="lg:hidden"
                :invoices="invoices"
                :invoice_statuses="invoice_statuses"
            />

            <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:block">
                    <DataTable :value="invoices" tableStyle="min-width: 50rem">
                    <Column field="number" header="Číslo faktúry"></Column>
                    <Column field="recipient_name" header="Klient"></Column>
                    <Column field="created_at" header="Vytvorené">
                        <template #body="{ data }">{{ formatDate(data.created_at) }}</template>
                    </Column>
                    <Column field="total_price" header="Suma">
                        <template #body="{ data }">{{ formatAmount(data.total_price) }} €</template>
                    </Column>
                    <Column field="invoice_status_id" header="Stav">
                        <template #body="{ data }">
                            <AppSelect
                                :model-value="data.invoice_status_id"
                                :options="statusOptions"
                                size="small"
                                @update:model-value="(value) => updateStatus(data, value)"
                            />
                        </template>
                    </Column>
                    <Column header="Akcie">
                        <template #body="{ data }">
                            <a :href="route('invoices.pdf', data.id)" target="_blank" rel="noopener noreferrer" class="inline-flex" title="Stiahnuť PDF">
                                <Button icon="pi pi-file-pdf" class="p-button-text p-button-sm" />
                            </a>
                            <Link :href="route('invoices.edit', data.id)">
                                <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Upraviť" />
                            </Link>
                            <Button
                                icon="pi pi-trash"
                                class="p-button-text p-button-sm p-button-danger"
                                title="Zmazať"
                                @click="confirmDeleteInvoice(data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AppSelect from '@/Components/AppSelect.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InvoiceCardList from '@/Pages/Invoices/Components/InvoiceCardList.vue';
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import { formatAmount, formatDate } from '@/utils/formatters';

interface InvoiceStats {
    total_invoiced: number
    paid: number
    awaiting: number
    overdue: number
    draft: number
}

const props = defineProps<{
    invoices: App.Http.Resources.InvoiceResource[]
    invoice_stats: InvoiceStats
    invoice_statuses: App.Http.Resources.InvoiceStatusResource[]
}>();

const statusOptions = computed(() =>
    (props.invoice_statuses || []).map((s) => ({
        value: s.id,
        label: s.name || s.code || String(s.id),
    }))
);

function updateStatus(invoice: App.Http.Resources.InvoiceResource, newStatusId: unknown): void {
    const id = newStatusId != null ? Number(newStatusId) : null;
    if (id === invoice.invoice_status_id) return;
    router.patch(route('invoices.update-status', invoice.id), { invoice_status_id: id });
}

function confirmDeleteInvoice(invoice: App.Http.Resources.InvoiceResource): void {
    if (!confirm(`Zmazať faktúru ${invoice.number}?`)) return;
    router.delete(route('invoices.destroy', invoice.id));
}
</script>
