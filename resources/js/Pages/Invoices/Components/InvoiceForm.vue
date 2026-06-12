<template>
    <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
        <PageHeader :title="heading">
            <template #actions>
                <Link :href="route('invoices')">
                    <Button label="Späť na faktúry" icon="pi pi-arrow-left" class="p-button-raised p-button-sm" />
                </Link>
            </template>
        </PageHeader>

        <form class="space-y-8" @submit.prevent="submit">
            <InvoiceHeaderForm
                v-model="header"
                :currencies="currencies"
                :errors="errors"
                id-prefix="invoice"
            />

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <InvoiceSettings
                    mode="invoice"
                    :user="user"
                    id-prefix="invoice-settings"
                />
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <IssuerDetailsForm
                    :model-value="form.issuer"
                    :errors="errors"
                    id-prefix="invoice-issuer"
                />
            </div>

            <InvoiceRecipientSection
                v-model:recipient-id="form.recipient_id"
                :recipients="recipients"
                :preselected-recipient="preselectedRecipient"
                :recipient="form.recipient"
                :description="recipientDescription"
                :errors="errors"
            />

            <InvoiceItemsTable
                v-model="form.items"
                :currency-symbol="currencySymbol"
                :vat-types="vatTypes"
                :error="errors.items"
            />

            <div class="flex justify-end">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full min-w-[200px] rounded-lg bg-gray-800 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    {{ form.processing ? processingLabel : submitLabel }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import Button from 'primevue/button';
import PageHeader from '@/Components/PageHeader.vue';
import { Link, usePage } from '@inertiajs/vue3';
import InvoiceHeaderForm from './InvoiceHeaderForm.vue';
import InvoiceItemsTable from './InvoiceItemsTable.vue';
import InvoiceRecipientSection from './InvoiceRecipientSection.vue';
import InvoiceSettings from './InvoiceSettings.vue';
import IssuerDetailsForm from './IssuerDetailsForm.vue';
import { useInvoiceForm } from '../Composables/useInvoiceForm';

const props = defineProps({
    mode: {
        type: String,
        default: 'create',
        validator: (value) => ['create', 'edit'].includes(value),
    },
    invoice: {
        type: Object,
        default: null,
    },
    recipients: {
        type: Array,
        default: () => [],
    },
    suggestedNumber: {
        type: String,
        default: '',
    },
    preselectedRecipient: {
        type: Object,
        default: null,
    },
    currencies: {
        type: Array,
        default: () => [],
    },
    vatTypes: {
        type: Array,
        default: () => [],
    },
    defaultCurrencyId: {
        type: [Number, String],
        default: null,
    },
});

const user = usePage().props.auth?.user;

const {
    form,
    header,
    errors,
    heading,
    recipientDescription,
    submitLabel,
    processingLabel,
    currencySymbol,
    hasUserBillingDetails,
    submit,
} = useInvoiceForm(props, user);
</script>
