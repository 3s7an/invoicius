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

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceForm from './Components/InvoiceForm.vue';

defineProps({
    recipients: {
        type: Array,
        default: () => [],
    },
    suggested_number: {
        type: String,
        default: '',
    },
    preselected_recipient: {
        type: Object,
        default: null,
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

