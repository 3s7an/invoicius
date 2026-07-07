<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppSelect from '@/Components/AppSelect.vue';
import Button from 'primevue/button';
import { formatAmount, formatDate } from '@/utils/formatters';
import { invoiceStatusLabel } from '@/Pages/Invoices/Utils/helpers';
import type { InvoiceResource, InvoiceStatusResource } from '@/types';

const { t } = useI18n();

const props = defineProps<{
    invoices: InvoiceResource[]
    invoice_statuses: InvoiceStatusResource[]
}>();

const emit = defineEmits<{ edit: [InvoiceResource] }>();

const statusOptions = computed(() =>
    (props.invoice_statuses ?? []).map((s) => ({
        value: s.id,
        label: invoiceStatusLabel(s.code, s.name),
    })),
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

<template>
    <div v-if="invoices.length === 0" class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm">
        {{ t('invoices.table.empty') }}
    </div>

    <div v-else class="space-y-3">
        <article
            v-for="invoice in invoices"
            :key="invoice.id"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
        >
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="truncate text-base font-semibold text-gray-900">{{ invoice.number }}</p>
                    <p class="mt-0.5 truncate text-sm text-gray-600">{{ invoice.recipient_name || '—' }}</p>
                </div>
                <p class="shrink-0 text-base font-semibold text-gray-900">
                    {{ formatAmount(invoice.total_price) }} €
                </p>
            </div>

            <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.table.created') }}</dt>
                    <dd class="mt-0.5 text-gray-900">
                        {{ invoice.created_at ? formatDate(invoice.created_at) : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.table.status') }}</dt>
                    <dd class="mt-0.5">
                        <AppSelect
                            :model-value="invoice.invoice_status_id"
                            :options="statusOptions"
                            size="small"
                            @update:model-value="(value: unknown) => updateStatus(invoice, value)"
                        />
                    </dd>
                </div>
            </dl>

            <div class="mt-4 flex items-center justify-end gap-1 border-t border-gray-100 pt-3">
                <a
                    :href="route('invoices.pdf', invoice.id)"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex"
                    :title="t('invoices.table.downloadPdf')"
                >
                    <Button icon="pi pi-file-pdf" class="p-button-text p-button-sm" />
                </a>
                <Button
                    icon="pi pi-pencil"
                    class="p-button-text p-button-sm"
                    :title="t('common.edit')"
                    @click="emit('edit', invoice)"
                />
                <Button
                    icon="pi pi-trash"
                    class="p-button-text p-button-sm p-button-danger"
                    :title="t('common.delete')"
                    @click="confirmDeleteInvoice(invoice)"
                />
            </div>
        </article>
    </div>
</template>
