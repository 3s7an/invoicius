<template>
    <Head :title="t('invoices.form.editInvoice', { number: invoice?.number ?? '' })" />

    <AuthenticatedLayout>
        <InvoiceForm
            mode="edit"
            :invoice="invoice"
            :recipients="recipients"
            :currencies="currencies"
            :vat-types="vat_types"
            :default-currency-id="default_currency_id"
        />
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceForm from './Components/InvoiceForm.vue';
import type { InvoiceResource, RecipientResource, CurrencyResource, VatTypeResource } from '@/types';

const { t } = useI18n();

defineProps<{
    invoice: InvoiceResource
    recipients?: RecipientResource[]
    currencies?: CurrencyResource[]
    vat_types?: VatTypeResource[]
    default_currency_id?: number | null
}>();
</script>
