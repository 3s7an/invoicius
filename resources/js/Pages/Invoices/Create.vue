<template>
    <Head title="Nová faktúra" />

    <AuthenticatedLayout>
        <InvoiceForm
            mode="create"
            :recipients="recipients"
            :suggested-number="suggested_number"
            :preselected-recipient="preselected_recipient"
            :currencies="currencies"
            :vat-types="vat_types"
            :default-currency-id="default_currency_id"
        />
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceForm from './Components/InvoiceForm.vue';

defineProps<{
    recipients: App.Http.Resources.RecipientResource[]
    suggested_number?: string
    preselected_recipient?: App.Http.Resources.RecipientResource | null
    currencies: App.Http.Resources.CurrencyResource[]
    vat_types: App.Http.Resources.VatTypeResource[]
    default_currency_id?: number | null
}>();
</script>
