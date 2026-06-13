<template>
    <Head :title="'Upraviť faktúru ' + (invoice?.number ?? '')" />

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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceForm from './Components/InvoiceForm.vue';

defineProps<{
    invoice: App.Http.Resources.InvoiceResource
    recipients?: App.Http.Resources.RecipientResource[]
    currencies?: App.Http.Resources.CurrencyResource[]
    vat_types?: App.Http.Resources.VatTypeResource[]
    default_currency_id?: number | null
}>();
</script>
