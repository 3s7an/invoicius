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


<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceForm from './Components/InvoiceForm.vue';

defineProps({
    invoice: {
        type: Object,
        required: true,
    },
    recipients: {
        type: Array,
        default: () => [],
    },
    currencies: {
        type: Array,
        default: () => [],
    },
    vat_types: {
        type: Array,
        default: () => [],
    },
    default_currency_id: {
        type: [Number, String],
        default: null,
    },
});

</script>
