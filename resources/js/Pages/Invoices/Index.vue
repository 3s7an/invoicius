<template>
    <Head title="Faktúry" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <PageHeader title="Faktúry">
                <template #actions>
                    <Button label="Nová faktúra" icon="pi pi-plus" class="p-button-raised p-button-sm" @click="openCreate" />
                </template>
            </PageHeader>
            <InvoiceCardList
                class="lg:hidden"
                :invoices="invoices"
                :invoice_statuses="invoice_statuses"
                @edit="openEdit"
            />

            <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:block">
                <DataTable :value="invoices" tableStyle="min-width: 50rem">
                    <Column field="number" header="Číslo faktúry" />
                    <Column field="recipient_name" header="Klient" />
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
                                @update:model-value="(value: unknown) => updateStatus(data, value)"
                            />
                        </template>
                    </Column>
                    <Column header="Akcie">
                        <template #body="{ data }">
                            <a :href="route('invoices.pdf', data.id)" target="_blank" rel="noopener noreferrer" class="inline-flex" title="Stiahnuť PDF">
                                <Button icon="pi pi-file-pdf" class="p-button-text p-button-sm" />
                            </a>
                            <Button
                                icon="pi pi-pencil"
                                class="p-button-text p-button-sm"
                                title="Upraviť"
                                @click="openEdit(data)"
                            />
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

    <InvoiceDrawer
        :key="editingInvoice?.id ?? 'create'"
        :show="showDrawer"
        :invoice="editingInvoice"
        :recipients="recipients"
        :suggested-number="suggested_number"
        :currencies="currencies"
        :vat-types="vat_types"
        :default-currency-id="default_currency_id"
        @close="showDrawer = false"
    />
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import AppSelect from '@/Components/AppSelect.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InvoiceCardList from '@/Pages/Invoices/Components/InvoiceCardList.vue';
import InvoiceDrawer from '@/Pages/Invoices/Components/InvoiceDrawer.vue';
import { Head, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import { formatAmount, formatDate } from '@/utils/formatters';
import type { InvoiceResource, InvoiceStatusResource, RecipientResource, CurrencyResource, VatTypeResource } from '@/types';

interface InvoiceStats {
    total_invoiced: number
    paid: number
    awaiting: number
    overdue: number
    draft: number
}

const props = defineProps<{
    invoices: InvoiceResource[]
    invoice_stats: InvoiceStats
    invoice_statuses: InvoiceStatusResource[]
    recipients: RecipientResource[]
    currencies: CurrencyResource[]
    vat_types: VatTypeResource[]
    suggested_number: string
    default_currency_id?: number | null
    editing_invoice?: InvoiceResource | null
}>();

const showDrawer = ref(!!props.editing_invoice);
const editingInvoice = ref<InvoiceResource | null>(props.editing_invoice ?? null);

function openCreate() {
    editingInvoice.value = null;
    showDrawer.value = true;
}

function openEdit(invoice: InvoiceResource) {
    editingInvoice.value = invoice;
    showDrawer.value = true;
}

const statusOptions = computed(() =>
    (props.invoice_statuses || []).map((s) => ({
        value: s.id,
        label: s.name || s.code || String(s.id),
    }))
);

function updateStatus(invoice: InvoiceResource, newStatusId: unknown): void {
    const id = newStatusId != null ? Number(newStatusId) : null;
    if (id === invoice.invoice_status_id) return;
    router.patch(route('invoices.update-status', invoice.id), { invoice_status_id: id });
}

function confirmDeleteInvoice(invoice: InvoiceResource): void {
    if (!confirm(`Zmazať faktúru ${invoice.number}?`)) return;
    router.delete(route('invoices.destroy', invoice.id));
}
</script>
