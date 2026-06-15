<template>
    <div class="space-y-8">
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
                    v-model="form.issuer"
                    :errors="errors"
                    id-prefix="invoice-issuer"
                />
            </div>

            <InvoiceRecipientSection
                v-model:recipient-id="form.recipient_id"
                v-model:recipient="form.recipient"
                :recipients="recipients"
                :preselected-recipient="preselectedRecipient"
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
                    class="w-full min-w-[200px] rounded-lg bg-emerald-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    {{ form.processing ? processingLabel : submitLabel }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import Button from 'primevue/button';
import PageHeader from '@/Components/PageHeader.vue';
import { Link, usePage } from '@inertiajs/vue3';
import InvoiceHeaderForm from './InvoiceHeaderForm.vue';
import InvoiceItemsTable from './InvoiceItemsTable.vue';
import InvoiceRecipientSection from './InvoiceRecipientSection.vue';
import InvoiceSettings from './InvoiceSettings.vue';
import IssuerDetailsForm from './IssuerDetailsForm.vue';
import { useInvoiceForm } from '../Composables/useInvoiceForm';
import type { AuthUser } from '@/types/inertia';
import type { InvoiceResource, RecipientResource, CurrencyResource, VatTypeResource } from '@/types';

interface Props {
    mode?: 'create' | 'edit'
    invoice?: InvoiceResource | null
    recipients?: RecipientResource[]
    suggestedNumber?: string
    preselectedRecipient?: RecipientResource | null
    currencies?: CurrencyResource[]
    vatTypes?: VatTypeResource[]
    defaultCurrencyId?: number | null
}

const props = withDefaults(defineProps<Props>(), {
    mode: 'create',
    invoice: null,
    recipients: () => [],
    suggestedNumber: '',
    preselectedRecipient: null,
    currencies: () => [],
    vatTypes: () => [],
    defaultCurrencyId: null,
});

const user = (usePage().props as { auth?: { user: AuthUser } }).auth?.user;

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
