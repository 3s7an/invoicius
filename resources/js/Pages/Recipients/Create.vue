<template>
    <Head title="Nový klient" />

    <AuthenticatedLayout>
        <RecipientForm mode="create" :from-invoice="from_invoice" />
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import RecipientForm from './Components/RecipientForm.vue';

defineProps<{
    from_invoice?: boolean
}>();
</script>
