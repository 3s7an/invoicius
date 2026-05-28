<template>
    <Head :title="'Upraviť: ' + (recipient.company_name || recipient.name || 'Klient')" />

    <AuthenticatedLayout>
        <RecipientForm mode="edit" :recipient="recipient" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import RecipientForm from './Components/RecipientForm.vue';

defineProps({
    recipient: {
        type: Object,
        required: true,
    },
});
</script>
