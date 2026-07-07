<template>
    <Head :title="t('recipients.form.newTitle')" />

    <AuthenticatedLayout>
        <RecipientForm mode="create" :from-invoice="from_invoice" />
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import RecipientForm from './Components/RecipientForm.vue';

const { t } = useI18n();

defineProps<{
    from_invoice?: boolean
}>();
</script>
