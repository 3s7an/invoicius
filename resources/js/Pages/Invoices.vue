<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceOverviewCards from '@/Components/InvoiceOverviewCards.vue';
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import { formatAmount, formatDate } from '@/utils/formatters';

const props = defineProps({
    invoices: {
        type: Array,
        default: () => [],
    },
    invoice_stats: {
        type: Object,
        default: () => ({ total_invoiced: 0, paid: 0, awaiting: 0, overdue: 0 }),
    },
    invoice_statuses: {
        type: Array,
        default: () => [],
    },
});

const statusOptions = computed(() =>
    (props.invoice_statuses || []).map((s) => ({
        value: s.id,
        label: s.name || s.code || String(s.id),
    }))
);

function updateStatus(invoice, newStatusId) {
    const id = newStatusId != null ? Number(newStatusId) : null;
    if (id === invoice.invoice_status_id) return;
    router.patch(route('invoices.update-status', invoice.id), { invoice_status_id: id });
}

function confirmDeleteInvoice(invoice) {
    if (!confirm(`Delete invoice ${invoice.number}?`)) return;
    router.delete(route('invoices.destroy', invoice.id));
}
</script>

<template>
    <Head title="Invoices" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-lg font-medium text-gray-900">Invoices</h1>
                <Link :href="route('invoices.create')">
                    <Button label="New invoice" icon="pi pi-plus" class="p-button-raised p-button-sm" />
                </Link>
            </div>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <DataTable :value="invoices" tableStyle="min-width: 50rem">
                    <Column field="number" header="Invoice number"></Column>
                    <Column field="recipient_name" header="Recipient"></Column>
                    <Column field="created_at" header="Created At">
                        <template #body="{ data }">{{ formatDate(data.created_at) }}</template>
                    </Column>
                    <Column field="total_price" header="Amount">
                        <template #body="{ data }">{{ formatAmount(data.total_price) }} €</template>
                    </Column>
                    <Column field="invoice_status_id" header="Status">
                        <template #body="{ data }">
                            <select
                                :value="data.invoice_status_id"
                                @change="updateStatus(data, $event.target.value)"
                                class="rounded-md border-gray-300 py-1.5 pl-2 pr-8 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option
                                    v-for="opt in statusOptions"
                                    :key="opt.value"
                                    :value="opt.value"
                                >
                                    {{ opt.label }}
                                </option>
                            </select>
                        </template>
                    </Column>
                    <Column header="Actions">
                        <template #body="{ data }">
                            <a :href="route('invoices.pdf', data.id)" target="_blank" rel="noopener noreferrer" class="inline-flex" title="Download PDF">
                                <Button icon="pi pi-file-pdf" class="p-button-text p-button-sm" />
                            </a>
                            <Link :href="route('invoices.edit', data.id)">
                                <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Edit" />
                            </Link>
                            <Button
                                icon="pi pi-trash"
                                class="p-button-text p-button-sm p-button-danger"
                                title="Delete"
                                @click="confirmDeleteInvoice(data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                </div>
        </div>
    </AuthenticatedLayout>
</template>
