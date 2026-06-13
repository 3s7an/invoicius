<template>
    <Head :title="'Upraviť: ' + (recipient.company_name || recipient.name || 'Klient')" />

    <AuthenticatedLayout>
        <RecipientForm mode="edit" :recipient="recipient" />
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import RecipientForm from './Components/RecipientForm.vue';

defineProps<{
    recipient: App.Http.Resources.RecipientResource
}>();
</script>
