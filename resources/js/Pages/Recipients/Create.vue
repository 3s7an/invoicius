<template>
    <Head title="Nový odberateľ" />

    <AuthenticatedLayout>
        <RecipientForm mode="create" :from-invoice="from_invoice" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import RecipientForm from './Components/RecipientForm.vue';

defineProps({
    from_invoice: {
        type: Boolean,
        default: false,
    },
});
</script>
