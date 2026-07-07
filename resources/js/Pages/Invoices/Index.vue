<template>
    <Head :title="t('invoices.pageTitle')" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <PageHeader :title="t('invoices.pageTitle')">
                <template #actions>
                    <Button :label="t('invoices.newInvoice')" icon="pi pi-plus" class="p-button-raised p-button-sm" @click="openCreate" />
                </template>
            </PageHeader>
            <InvoiceCardList
                class="lg:hidden"
                :invoices="invoices"
                :invoice_statuses="invoice_statuses"
                @edit="openEdit"
            />

            <div class="hidden lg:block overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
                <table class="w-full border-collapse text-sm" style="min-width:660px">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th v-for="h in headers" :key="h"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.05em] text-gray-500 whitespace-nowrap"
                                :class="h === t('invoices.table.amount') ? 'text-right' : ''"
>
                                {{ h }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="invoice in invoices" :key="invoice.id" class="border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-gray-900 whitespace-nowrap">{{ invoice.number }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ invoice.recipient_name || '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ invoice.created_at ? formatDate(invoice.created_at) : '—' }}</td>
                            <td class="px-4 py-3 text-right font-semibold tabular-nums text-gray-900 whitespace-nowrap">{{ formatAmount(invoice.total_price) }} €</td>
                            <td class="px-4 py-3" style="width:180px">
                                <AppSelect
                                    :model-value="invoice.invoice_status_id"
                                    :options="statusOptions"
                                    size="small"
                                    @update:model-value="(value: unknown) => updateStatus(invoice, value)"
                                />
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a :href="route('invoices.pdf', invoice.id)" target="_blank" rel="noopener noreferrer" class="inline-flex" :title="t('invoices.table.downloadPdf')">
                                    <Button icon="pi pi-file-pdf" class="p-button-text p-button-sm" />
                                </a>
                                <Button icon="pi pi-pencil" class="p-button-text p-button-sm" :title="t('common.edit')" @click="openEdit(invoice)" />
                                <Button icon="pi pi-trash" class="p-button-text p-button-sm p-button-danger" :title="t('common.delete')" @click="confirmDeleteInvoice(invoice)" />
                            </td>
                        </tr>
                        <tr v-if="invoices.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">{{ t('invoices.table.empty') }}</td>
                        </tr>
                    </tbody>
                </table>
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
import Button from 'primevue/button';
import { useI18n } from 'vue-i18n';
import { formatAmount, formatDate } from '@/utils/formatters';
import { invoiceStatusLabel } from '@/Pages/Invoices/Utils/helpers';
import type { InvoiceResource, InvoiceStatusResource, RecipientResource, CurrencyResource, VatTypeResource } from '@/types';

const { t } = useI18n();

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

const headers = computed(() => [
    t('invoices.table.number'),
    t('invoices.table.client'),
    t('invoices.table.created'),
    t('invoices.table.amount'),
    t('invoices.table.status'),
    '',
]);

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
        label: invoiceStatusLabel(s.code, s.name),
    }))
);

function updateStatus(invoice: InvoiceResource, newStatusId: unknown): void {
    const id = newStatusId != null ? Number(newStatusId) : null;
    if (id === invoice.invoice_status_id) return;
    router.patch(route('invoices.update-status', invoice.id), { invoice_status_id: id });
}

function confirmDeleteInvoice(invoice: InvoiceResource): void {
    if (!confirm(t('invoices.table.deleteConfirm', { number: invoice.number }))) return;
    router.delete(route('invoices.destroy', invoice.id));
}
</script>
